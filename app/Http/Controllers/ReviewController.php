<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Review;
use Cassandra\Exception\ValidationException;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = array(
                0 => 'id',
                1 => 'author',
                2 => 'text',
                3 => 'likes_count',
                4 => 'created_at'
            );

            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $data = Review::query()->withCount('likes')
                    ->groupBy('reviews.id')
                    ->orderBy($order,$dir)->get();

            return Datatables::of($data)
               ->addIndexColumn()
               ->addColumn('action', function($row){
                   return '
                    <a class="btn btn-danger btn-sm like" href="javascript:void(0)" data-id="'.
                       $row['id'].'"><span class="fa fa-heart"></span></a>
                    <a class="btn btn-primary btn-sm" href="/show/'.
                       $row['id'].'"><span class="fa fa-eye"></span></a>&nbsp;';
               })
               ->rawColumns(['action'])
               ->make(true);

        }

        return view('reviews.index');
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return response(view('reviews.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
       $validatedData = $request->validate([
            'text'          => 'required|unique:reviews|max:16777215',
            'author'        => 'required|max:250',
       ]);

       preg_match('/<\s*.[^>]*>/u', $request->get('text'), $matches);
       if (!empty($matches)) {
            return redirect()->route('reviews.index')
                             ->with('error', 'Неверный формат отзыва.');
       }


       $validatedData['ip_address'] = $request->getClientIp();

        Review::query()->create($validatedData);

        return redirect()->route('reviews.index')
            ->with('success', 'Отзыв добавлен.');
    }

    /**
     * Display the specified resource.
     *
     * @param Review $review
     * @return Application|Factory|View|Response
     */
    public function show(Review $review)
    {
        $prev = Review::query()->where('id', '<', $review->id)->max('id');
        $next = Review::query()->where('id', '>', $review->id)->min('id');

        return view('reviews.show', compact(['review', 'next', 'prev']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'id'            => 'required|exists:reviews',
        ]);

        $like_exists = Like::where([
            'review_id'  => $request->get('id'),
            'ip_address' => $request->ip()
        ])->count();

        if ($like_exists) {
            return response()->json([
                'error' => 'Повторное действие запрещено.',
            ], 400);
        }

        $like = new Like();
        $like->review_id  = $request->id;
        $like->ip_address = $request->ip();
        $like->save();

        return response()->json([
                'success' => 'ok',
            ]);
    }
}

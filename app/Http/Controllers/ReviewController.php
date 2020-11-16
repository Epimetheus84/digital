<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Review;
use Cassandra\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = array(
                0 => 'id',
                1 => 'created_at',
                2 => 'likes_count'
            );

            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $data = Review::select(['*', DB::raw('count(likes.id) as likes_count')])
                    ->leftJoin('reviews', 'reviews.id', '=', 'likes.review_id')
                    ->groupBy('revews.id')
                    ->orderBy($order,$dir);

            return Datatables::of($data)
               ->addIndexColumn()
               ->addColumn('action', function($row){
                   return '<a class="btn btn-primary btn-sm" href="/reviews/'.
                       $row['id'].'"><span class="fa fa-eye"></span></a>&nbsp;<a data-id="'.
                       $row['id'] .'" href="javascript:void(0)" class="delete btn btn-danger btn-sm"><span class="fa fa-times"></span></a>';
               })
               ->rawColumns(['action'])
               ->make(true);

        }

        return view('reviews.index');
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(view('reviews.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'text'          => 'required|regex:/^(^\<(/?[^\>]+)\>+)$/u|unique:reviews|max:16777215',
            'author'        => 'required|max:250',
        ]);

        Review::query()->create($request->validated());

        return redirect()->route('reviews.index')
            ->with('success', 'Отзыв добавлен.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        $prev = Review::where('id', '<', $review->id)->max('id');
        $next = Review::where('id', '>', $review->id)->max('id');

        return view('reviews.show', compact(['review', 'next', 'prev']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like(Request $request)
    {
        $request->validate([
            'id'            => 'required|exists:reviews',
        ]);

        $like_exists = Like::where([
            'review_id'  => $request->get('id'),
            'ip_address' => $request->ip()
        ])->count();

        if ($like_exists) {
            return redirect()->route('reviews.index')
                             ->with('error', 'Повторное действие запрещено.');
        }

        return redirect()->route('reviews.index')
            ->with('success', 'Запись отмечена как понравившаяся.');
    }

}

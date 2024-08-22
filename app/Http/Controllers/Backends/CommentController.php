<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\helpers\GlobalFunction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CommentController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('comment.view')) {
            abort(403, 'Unauthorized action.');
        }
        $comments = Comment::whereNull('parent_id')->latest('id')->paginate(10);
        GlobalFunction::seenNotification('App\Models\Comment');
        return view('backends.comment.index',compact('comments'));
    }
    public function send(Request $request)
    {
        if(!auth()->guard('customer')->check()){
            $output = [
                'success' => 0,
                'msg' => __('Please login first')
            ];
            return response()->json($output);
        }else{
            try{
                DB::beginTransaction();
                $blog = Blog::find($request->blog_id);
                $comment = new Comment();
                $comment->customer_id = auth()->guard('customer')->user()->id;
                $comment->content = $request->input('content');
                $comment->blog_id = $request->blog_id;
                $comment->type = 'send';
                $comment->save();
                $comments = Comment::where('blog_id', $request->blog_id)->whereNull('parent_id')->get();
                DB::commit();
                GlobalFunction::storeNotification('App\Models\Comment', $comment->id);
                $output = [
                    'success' => 1,
                    'msg' => __('Comment successfully'),
                    'comment' => view('frontends.blog.comment')->with(['comments'=> $comments, 'blog' => $blog])->render(),
                ];
            }catch(Exception $e){
                DB::rollBack();
                $output = [
                    'success' => 0,
                    'msg' => __('Something went wrong'),
                ];
            }
            return response()->json($output);
       }

    }

    public function reply(Request $request, $id)
    {
        if(!auth()->guard('customer')->check()){
            $output = [
                'success' => 0,
                'msg' => __('Please login first')
            ];
            return response()->json($output);
        }else{
            try{
                DB::beginTransaction();
                $comment = Comment::findorfail($id);
                $reply = new Comment();
                $reply->customer_id = auth()->guard('customer')->user()->id;
                $reply->parent_id = $comment->id;
                $reply->blog_id = $comment->blog_id;
                $reply->content = $request->input('content');
                $reply->type = 'reply';
                $comment->replies()->save($reply);
                DB::commit();
                $output = [
                    'success' => 1,
                    'msg' => __('Reply successfully'),
                    'reply' => view('frontends.blog.reply', ['comment' => $comment])->render(),
                ];
            }catch(Exception $e){
                dd($e);
                DB::rollBack();
                $output = [
                    'success' => 0,
                    'msg' => __('Something went wrong')
                ];
            }
            return response()->json($output);
        }
    }
    public function show($id)
    {
        if (!auth()->user()->can('comment.create')) {
            abort(403, 'Unauthorized action.');

        }
        $comment = Comment::findorfail($id);
        return view('backends.comment.view', compact('comment'));
    }
    public function adminReply(Request $request, $id)
    {
        if (!auth()->user()->can('comment.create')) {
            abort(403, 'Unauthorized action.');

        }
        try{
            DB::beginTransaction();
            $comment = Comment::findorfail($id);
            $reply = new Comment();
            $reply->admin_id = auth()->user()->id;
            $reply->parent_id = $comment->id;
            $reply->blog_id = $comment->blog_id;
            $reply->content = $request->input('content');
            $reply->type = 'reply';
            $comment->replies()->save($reply);
            DB::commit();

            $output = [
                'success' => 1,
                'msg' => __('Reply successfully')
            ];

        }catch(Exception $e){
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }
        return redirect()->route('admin.comment.index')->with($output);
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('comment.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try{
            DB::beginTransaction();
            $comment = Comment::findOrFail($id);
            $comment->delete();
            $comments = Comment::whereNull('parent_id')->latest('id')->paginate(10);
            $view = view('backends.comment._table', compact('comments'))->render();

            DB::commit();
            $output = [
                'status' => 1,
                'view' => $view,
                'msg' => __('Deleted successfully')
            ];

        }catch(Exception $e){
            // dd($e);
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong'),
            ];
        }
        return response()->json($output);
    }
    public function updateStatus (Request $request)
    {
        try {
            DB::beginTransaction();

            $comment = Comment::findOrFail($request->id);
            $comment->status = $comment->status == 'active' ? 'inactive' : 'active';
            $comment->save();

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {
            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Posts;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/posts",
     *      operationId="getPostsList",
     *      tags={"Posts"},
     *      summary="Get list of posts",
     *      description="Returns list of posts",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      )
     *     )
     */
    public function index(){
        try {
            return PostResource::collection(Posts::paginate(request()->all()));
        }catch (Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/posts/{id}",
     *      operationId="getPostsById",
     *      tags={"Posts"},
     *      summary="Get post information",
     *      description="Returns post data",
     *      @OA\Parameter(
     *          name="id",
     *          description="post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      )
     *     )
     */
    public function show($id){
        try {
            return new PostResource(Posts::find($id));
        }catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

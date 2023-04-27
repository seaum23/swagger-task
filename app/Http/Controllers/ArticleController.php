<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     *
     * @OA\Get(
     *   path="/article",
     *   summary="Get all articles",
     *   description="Get articles of all user!",
     *   operationId="articleIndex",
     *   tags={"article"},
     *   security={ {"bearer": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Article"))
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="User should be authorized to get profile information",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Not authorized"),
     *      )
     *   )
     * )
     */
    public function index()
    {
        return response()->json(['data' => Article::with('user')->get()]);
    }

    /**
     * @OA\Post(
     *   path="/article",
     *   summary="Store article",
     *   description="Create a new article for a specified user",
     *   operationId="articleStore",
     *   tags={"article"},
     *   security={ {"bearer": {} }},
     *   @OA\RequestBody(
     *      required=true,
     *      description="New article data",
     *      @OA\JsonContent(
     *         required={"title","description","user_id"},
     *         @OA\Property(property="title", type="string", example="Demo Article"),
     *         @OA\Property(property="description", type="string", example="This is the description of the demo article"),
     *         @OA\Property(property="user_id", type="string", description="ID of user", example="1")
     *      ),
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Wrong credentials response",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Unauthorized")
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *      description="Successfully created",
     *      @OA\JsonContent(
     *         @OA\Property(property="article", type="object", ref="#/components/schemas/Article"),
     *      )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="title",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The title field is required."},
     *              )
     *           ),
     *           @OA\Property(
     *              property="description",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The description field is required."},
     *              )
     *           ),
     *           @OA\Property(
     *              property="user_id",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The user_id field is required."},
     *              )
     *           )
     *        )
     *     )
     *   )
     * )
     */
    public function store(ArticleRequest $request)
    {
        return response()->json(Article::create($request->validated())->load('user'), 201);
    }

    /**
     *
     * @OA\Get(
     *   path="/article/{article_id}",
     *   summary="Get specific article",
     *   description="Get a specific article!",
     *   operationId="articleShow",
     *   tags={"article"},
     *   security={ {"bearer": {} }},
     *   @OA\Parameter(
     *      name="article_id",
     *      in="path",
     *      description="Article id",
     *      required=true,
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *         @OA\Property(property="data", type="object", ref="#/components/schemas/Article")
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="User should be authorized to get profile information",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Not authorized"),
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Error: Not Found",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Record not found.")
     *      )
     *   )
     * )
     */
    public function show($article_id)
    {
        return response()->json(['data' => Article::with('user')->findOrFail($article_id)]);
    }

    /**
     *
     * @OA\Get(
     *   path="/article/user/{user_id}",
     *   summary="Get specified users articles",
     *   description="Get articles of specified user!",
     *   operationId="articleUserShow",
     *   tags={"article"},
     *   security={ {"bearer": {} }},
     *   @OA\Parameter(
     *      name="user_id",
     *      in="path",
     *      description="User id",
     *      required=true,
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Article"))
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="User should be authorized to get profile information",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Not authorized"),
     *      )
     *   )
     * )
     */
    public function userArticle($user_id)
    {
        return response()->json(['data' => Article::with('user')->where('user_id', $user_id)->get()]);
    }

    /**
     * @OA\Patch(
     *   path="/article/{article}",
     *   summary="Update article",
     *   description="Update specified article data",
     *   operationId="articleUpdate",
     *   tags={"article"},
     *   security={ {"bearer": {} }},
     *   @OA\RequestBody(
     *      required=true,
     *      description="Updated Article data",
     *      @OA\JsonContent(
     *         required={"title","description","user_id"},
     *         @OA\Property(property="title", type="string", example="Demo Article"),
     *         @OA\Property(property="description", type="string", example="This is the description of the demo article"),
     *         @OA\Property(property="user_id", type="string", description="ID of user", example="1")
     *      ),
     *   ),
     *   @OA\Parameter(
     *      name="article",
     *      in="path",
     *      description="Article id",
     *      required=true,
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Wrong credentials response",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Unauthorized")
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Error: Not Found",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Record not found.")
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *         @OA\Property(property="article", type="object", ref="#/components/schemas/Article"),
     *      )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="title",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The title field is required."},
     *              )
     *           ),
     *           @OA\Property(
     *              property="description",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The description field is required."},
     *              )
     *           ),
     *           @OA\Property(
     *              property="user_id",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The user_id field is required."},
     *              )
     *           )
     *        )
     *     )
     *   )
     * )
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->title = $request->title;
        $article->description = $request->description;
        $article->user_id = $request->user_id;

        if($article->isDirty()){
            $article->save();
        }

        return response()->json($article->load('user'));
    }

    /**
     * @OA\Delete(
     *   path="/article/{article}",
     *   summary="Delete article",
     *   description="Delete specified article",
     *   operationId="articleDelete",
     *   tags={"article"},
     *   security={ {"bearer": {} }},
     *   @OA\Parameter(
     *      name="article",
     *      in="path",
     *      description="Article id",
     *      required=true,
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Wrong credentials response",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Unauthorized")
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Error: Not Found",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Record not found.")
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent()
     *   )
     * )
     */
    public function destroy(Article $article)
    {
        $article->delete();
    }
}

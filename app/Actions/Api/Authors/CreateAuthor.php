<?php

namespace App\Actions\Api\Authors;

use App\Models\Author;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Resources\Author\AuthorResource;
use App\Http\Requests\Author\CreateAuthorRequest;

class CreateAuthor
{
    use AsAction;

    public function handle($request)
    {
        $author = Author::create($request->all());
        return $author;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(CreateAuthorRequest $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($author, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Author Created Successfully', 'data' => new AuthorResource($author)], 200);
        }
        return new AuthorResource($author);
    }
}

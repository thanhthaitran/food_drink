<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Note;
use Auth;
use App\Http\Requests\UpdateNoteRequest;

class NotesController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param Note $note note object
     *
     * @return \Illuminate\Http\Response
    */
    public function destroy(Note $note)
    {
        $note->delete();
        flash(trans('user.admin.message.success'))->success();
        return redirect()->route('order.show', ['order' => $note->order_id]);
    }

    /**
     * Update note of order
     *
     * @param \Illuminate\Http\Request $request request
     * @param \App\Note                $note    note
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        if($note->user_id == Auth::id()) {
            try {
                $note['content'] = $request->content;
                $note->save();
                return response()->json($note->load('order'));
            } catch (Exception $e) {
                return response(trans('message.post.fail_delete'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response(__('api.error_405'), Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}

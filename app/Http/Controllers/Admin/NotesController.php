<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Note;

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
}

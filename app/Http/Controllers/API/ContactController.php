<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ContactController extends BaseController {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        //
        $contact = Contact::all();
        return $this->sendResponse( ContactResource::collection( $contact ), 'Contacts fetched.' );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        //
        $input = $request->all();
        $validator = Validator::make( $input, [
            'phone_number' => 'required|min:11|Numeric|unique:contacts',
        ] );
        if ( $validator->fails() ) {
            return $this->sendError( $validator->errors() );

        }
        $input[ 'user_id' ] = Auth::id();
        $contact = Contact::create( $input );
        return $this->sendResponse( new ContactResource( $contact ), 'Contact created.' );
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Contact  $contact
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
        $contact = Contact::find( $id );
        if ( is_null( $contact ) ) {
            return $this->sendError( 'Contact does not exist.' );
        }
        return $this->sendResponse( new ContactResource( $contact ), 'Contact fetched.' );
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Contact  $contact
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, Contact $contact ) {
        //
        {
        $input = $request->all();

        $validator = Validator::make($input, [
        'phone_number' => 'required|min:11|Numeric|unique:contacts',

        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $contact->phone_number = $input['phone_number'];
        $contact->user_id = Auth::id();
        $contact->save();

        return $this->sendResponse(new ContactResource($contact), 'contact updated.');
    }
    }

        /**
        * Remove the specified resource from storage.
        *
        * @param  \App\Models\Contact  $contact
        * @return \Illuminate\Http\Response
        */

        public function destroy( $id ) {
            //

        $contact = Contact::find($id)->delete();
        return $this->sendResponse([], 'Contact deleted.');
        }
    }

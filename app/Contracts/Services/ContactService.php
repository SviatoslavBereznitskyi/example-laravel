<?php

namespace App\Contracts\Services;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\Profile;

interface ContactService
{
    public function store(CreateContactRequest $request, Profile $profile): Contact;

    public function delete(Contact $contact);

    public function update(UpdateContactRequest $request, Contact $contact): Contact;
}

@csrf

<label for="">Name</label>
<input class="form-control" type="text" name="name" value="{{ old('name', $permission->name) }}">

<button type="submit" class="btn btn-success mt-2">Send</button>
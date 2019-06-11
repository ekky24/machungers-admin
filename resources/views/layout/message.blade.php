@if(count($errors) > 0)
	@foreach($errors->all() as $error)
		<div class="alert alert-danger alert-dismissible fade in" style="display: none;">
			{{ $error }}
		</div>
	@endforeach
@endif

@if(session('success'))
	<div class="alert alert-success alert-dismissible fade in" style="display: none;">
		<center>{{ session('success') }}</center>
	</div>
@endif

@if(session('error'))
	<div class="alert alert-danger alert-dismissible fade in" style="display: none;">
		{{ session('error') }}
	</div>
@endif
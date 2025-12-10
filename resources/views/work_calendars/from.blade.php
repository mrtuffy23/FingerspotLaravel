@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ $record ? 'Edit Holiday' : 'Add Holiday' }}
    </div>
    <div class="card-body">

        <form method="POST" action="{{ $record ? route('work-calendars.update', $record) : route('work-calendars.store') }}">
            @csrf
            @if($record)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" value="{{ $date }}" {{ $record ? 'readonly' : '' }}>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" class="form-control" name="description" value="{{ $record->description ?? '' }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <select class="form-control" name="type">
                    <option value="workday" {{ (optional($record)->type=='workday')?'selected':'' }}>Workday</option>
                    <option value="national_holiday" {{ (optional($record)->type=='national_holiday')?'selected':'' }}>National Holiday</option>
                    <option value="collective_leave" {{ (optional($record)->type=='collective_leave')?'selected':'' }}>Collective Leave</option>
                    <option value="weekend" {{ (optional($record)->type=='weekend')?'selected':'' }}>Weekend</option>
                </select>
            </div>

            <button class="btn btn-primary">Save</button>
        </form>

    </div>
</div>
@endsection

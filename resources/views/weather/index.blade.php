@extends('layouts.app')

@section('title', "`Ang Buhay ay Weather Weather Lang` - Mr. Kim Atienza")

@section('content')
    <div class="container">
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <form method="GET" action="{{ route('index') }}">
                <div class="row">
                    <label class="col-md-2 col-form-label sr-only" for="query">Enter City / Country</label>
                    <div class="col-md-8">
                        <div class="input-group mb-2 mr-md-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Enter City / Country</div>
                            </div>

                            <input type="text" name="query" class="form-control" id="query" placeholder="Type your city or country here.." value="{{ $query }}" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mb-2">Submit</button>
                        <a href="{{ route('index', ['reset' => true]) }}" class="btn btn-secondary mb-2"><i class="fa fa-undo"></i></a>
                    </div>
                </div>
            </form>

            <h4 class="border-bottom border-gray pb-2 mb-0 mt-2">
                Current Temperature &nbsp; <i class="fa fa-temperature-empty"></i>
            </h4>
        </div>
        @if (isset($result['error']) && !$result['error'])
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h3>
                                <img src="{{ $result['img'] }}" />
                                {{ $result['temperature_c'] }}
                                ({{ $result['cloud'] }})
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h6>
                                @if (isset($result['is_cache']))
                                    <span class="text-info">(Last Saved)</span>
                                @endif
                                {{ $result['city'] ? $result['city'] . ',' : '' }} {{ $result['region'] . ", " . $result['country'] }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (isset($result['error']) && $result['error'])
            <div class="alert alert-danger alert-block">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ $result['message'] ?? 'Something went wrong! Please try again.' }}</strong>
            </div>
        @endif

        @if (isset($result['temperature_c']) && $result['temperature_c'])
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <form id="forecastForm">
                        <input type="hidden" name="city" value="{{ $result['city'] }}" />
                        <input type="hidden" name="region" value="{{ $result['region'] }}" />
                        <input type="hidden" name="country" value="{{ $result['country'] }}" />
                        <input type="hidden" name="temperature_c" value="{{ $result['temperature_c'] }}" />
                        <input type="hidden" name="cloud" value="{{ $result['cloud'] }}" />
                        <input type="hidden" name="img" value="{{ $result['img'] }}" />
                    </form>
                    @if (!isset($result['is_cache']))
                        <button type="button" class="btn btn-sm btn-primary" id="save">
                            <i id="btnSpinner" class="d-none fa fa-spinner fa-spin" aria-hidden="true"></i>
                            <span id="btnText">Save Weather Forecast</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('after-scripts')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("button#save").on("click", function(e) {
                e.preventDefault();
                var $this = $(this);

                $.ajax({
                    type : "POST",
                    url : "{{ route('store') }}",
                    data : $("#forecastForm").serialize(),
                    beforeSend : function() {
                        $('#btnSpinner').removeClass('d-none');
                        $('#btnText').addClass('d-none');
                    },
                    success : function(response) {
                        if (response.error == false) {
                            alert("Saved");
                        } else {
                            alert(response.message);
                        }
                    },
                    error : function (response) {
                        alert("There is an error in saving the weather forecast.");
                    },
                    complete : function() {
                        $('#btnSpinner').addClass('d-none');
                        $('#btnText').removeClass('d-none');
                    }
                });

            });
        });
    </script>
@endpush

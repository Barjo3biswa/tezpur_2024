<div class="card-body">
	{{ csrf_field() }}
	<div class="row">
		<div class="col-md-8 col-lg-8">
			<div class="form-group">
				<label class="form-label">Course <strong class="text-danger">*</strong></label>
				<select name="course_id" id="course" class="form-control input-sm js-example-basic-single"  style="width:100%" required>
					<option value="">Select Course</option>
					@foreach($courses as $course)
					<option value="{{$course->id}}" @isset($fee){{$fee->course_id==$course->id?'selected':''}}@endisset {{Request::old('course_id')==$course->id?'selected':''}}>{{$course->name}} ({{$course->code}})</option>
					@endforeach
				</select>
			</div>
		</div>
		@if(!isset($fee))
		<div class="col-md-4 col-lg-4">
			<div class="form-group">
				<label class="form-label">Select Category <strong class="text-danger">*</strong></label>
				<select name="admission_category_id" id="admission_category_id" class="form-control input-sm" required>
					<option value="">Select Year</option>
					<option value="1">General</option>
					<option value="other">Other</option>
				</select>
			</div>
		</div>
		@endif
		@php
		$f_year_zero = date('Y');
		$f_year_one = date('Y')+1;
		$f_year_two = date('Y')+2;
		@endphp
		<div class="col-md-4 col-lg-4">
			<div class="form-group">
				<label class="form-label">Financial Year <strong class="text-danger">*</strong></label>
				<select name="financial_year" class="form-control input-sm" required>
					<option value="">Select Year</option>
					<option value="{{$f_year_zero.'-'.$f_year_one}}" @isset($fee){{$fee->financial_year==($f_year_zero.'-'.$f_year_one)?'selected':''}}@endisset>{{$f_year_zero.'-'.$f_year_one}}</option>
					<option value="{{$f_year_one.'-'.$f_year_two}}" @isset($fee){{($fee->financial_year==($f_year_one.'-'.$f_year_two))?'selected':''}}@endisset>{{$f_year_one.'-'.$f_year_two}}</option>
				</select>
			</div>
		</div>
		<div class="col-md-4 col-lg-4">
			<div class="form-group">
				<label class="form-label">Year <strong class="text-danger">*</strong></label>
				<select name="year" class="form-control input-sm" required>
					<option value="">Select Year</option>
					<option value="{{$f_year_zero}}" @isset($fee){{$fee->year==($f_year_zero)?'selected':''}}@endisset>{{$f_year_zero}}</option>
					<option value="{{$f_year_one}}" @isset($fee){{$fee->year==($f_year_one)?'selected':''}}@endisset>{{$f_year_one}}</option>
				</select>
			</div>
		</div>
		<div class="col-md-4 col-lg-4">
			<div class="form-group">
				<label class="form-label">Hostel <strong class="text-danger">*</strong></label>
				<select name="hostel_required" class="form-control input-sm" required>
					<option value="" disabled selected>Select</option>
					<option value="yes" @isset($fee){{$fee->hostel_required ? 'selected':''}}@endisset> Required</option>
					<option value="no" @isset($fee){{$fee->hostel_required ?'':'selected'}}@endisset> Not Required</option>
				</select>
			</div>
		</div>
		
		
		<div class="col-md-4 col-lg-4">
			<div class="form-group">
				<label class="form-label">Type</label>
				<select name="type" class="form-control input-sm" required>
					<option value="">Select</option>
					<option value="admission" @isset($fee){{ $fee->type == "admission" ? 'selected' : ''}}@else {{__('selected')}} @endisset>Admission</option>
					<option value="examination" @isset($fee){{ $fee->type == 'examination' ? 'selected' : '' }}@endisset>Examination</option>
				</select>
			</div>
		</div>
		<div class="col-md-4 col-lg-4">
			<div class="form-group">
				<label class="form-label">Programm Type</label>
				<select name="programm_type" class="form-control input-sm" required>
					<option value="" selected disabled>Select</option>
					@foreach (\App\Models\MeritList::$programm_types as $type)
						<option value="{{$type}}" @isset($fee){{ $fee->programm_type == $type ? 'selected' : ''}} @endisset>{{ucwords(str_replace("_", " ", $type))}}</option>
					@endforeach
					{{-- <option value="full_time" @isset($fee){{ $fee->programm_type == "full_time" ? 'selected' : ''}} @endisset>Full Time</option>
					<option value="part_time" @isset($fee){{ $fee->programm_type == 'part_time' ? 'selected' : '' }}@endisset>Part Time</option>
					<option value="not_required" @isset($fee){{ $fee->programm_type == "not_required" ? 'selected' : ''}} @else {{__('selected')}}@endisset>Not Required</option> --}}
				</select>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-smaller-font" id="fee_head_table">
			<thead>
				<tr>
					<th>
						<label class="custom-control custom-checkbox custom-control-inline">
							<input type="checkbox" class="custom-control-input" id="global_required" checked>
							<span class="custom-control-label"></span>
						</label>
					</th>
					<th>Fee Head</th>
					<th>Amount</th>
					
				</tr>
			</thead>
			<tbody>
				@foreach($fee_heads as $index =>  $fee_head)
				@php
				if(isset($fee))
					$structure = $fee->feeStructures->where('fee_head_id',$fee_head->id)->first();
				@endphp
				<tr class="free-class required-class">
					<td>
						<label class="custom-control custom-checkbox custom-control-inline">
							<input type="checkbox" class="custom-control-input" name="is_required[{{$index}}]" value="1" id="required_fee_head" @if(isset($fee)) @if($structure)checked @endif @else checked @endif>
							<span class="custom-control-label"></span>
						</label>
					</td>
					<td>
						<input type="text" class="form-control input-sm " value="{{$fee_head->name}}" readonly>
						<input type="hidden" name="fee_heads[{{$index}}]" value="{{$fee_head->id}}" readonly>
					</td>
					<td>
						<input type="number" name="amounts[{{$index}}]" class="form-control input-sm amounts text-right" min="0" minlength="1" @if(isset($fee)) @if($structure) value="{{$structure->amount}}" @endif @else value="0" @endif>
					</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" class="text-right">Total</th>
					<th id="total" class="text-right">
						@if(isset($fee))
							{{number_format($fee->feeStructures->sum("amount"), 2)}}
						@else
							0.00
						@endif
					</th>
					<th id=""></th>
				</tr>

				<tr>
					<th colspan="3" class="text-center">
					<div class="hidden_admission_category"></div>
					</th>
					
				</tr>

			</tfoot>

		</table>
	</div>
</div>



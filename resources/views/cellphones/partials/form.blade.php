@csrf
<div class="col-md-6">
  <label for="model" class="form-label" >model</label>
  <input type="text" class="form-control" name="model" id="model" value="{{ old('model',$cellphone->model) }}" required>
</div>
<div class="col-md-6">
  <label for="brand" class="form-label" >Marca</label>
  <input type="text" class="form-control" name="brand" id="brand" value="{{old('brand',$cellphone->brand)}}"required>
</div>
<div class="col-md-6">
  <label for="imei" class="form-label" >imei</label>
  <input type="text" class="form-control" name="imei" id="imei" value="{{old('imei',$cellphone->imei)}}"required>
</div>
<div class="col-md-4">
  <label for="company_id" class="form-label" >Empresa Titular</label>
    <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="company_id" id="company_id" value="{{old('company_id',$cellphone->company_id)}}">
      
      @foreach ( $companies as $company )
        <option value="{{$company->id}}" @if ($company->id == old('company_id',$cellphone->company_id)) selected @endif>{{$company->company_name}}</option>
      @endforeach
      
    </select>
</div>
<div class="col-md-4">
  <label for="department_id" class="form-label" >Departamento Asignado</label>
  <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="department_id" id="department_id" value="{{old('department_id',$cellphone->department_id)}}">

    @foreach ( $departments as $department )
        <option value="{{$department->id}}" @if ($department->id == old('department_id',$cellphone->department_id)) selected @endif>{{$department->department_name}}</option>
    @endforeach
  </select>
</div>
<div class="col-md-4">
  <label for="status" class="form-label" >Estado</label>
  <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="status" id="status" >
    <option value="0" @if(old('status',$cellphone->status)) selected @endif >Disponible</option>
    <option value="1" @if(old('status',$cellphone->status)) selected @endif>Asignado</option>
  </select>
</div>
<div class="form-group col-md-12">
  <label for="accessories" class="form-label" >Accesorios</label>
  <input type="text" class="form-control" name="accessories" id="accessories" value="{{ old('accessories',$cellphone->accessories) }}">
</div>

<div class="col-6 m3">
  <button type="submit" class="btn btn-info">{{ $btnText }}</button>
</div>
<script src="{{ asset('/js/asignment/recived.js') }}"></script>

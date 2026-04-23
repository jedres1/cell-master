@csrf
@php
  $selectedCompanyId = old('company_id', $employee->company_id);
  $isCreatingCompany = $selectedCompanyId === '__new__';
@endphp
<div class="col-md-6">
  <label for="employee_name" class="form-label" >Nombre del empleado</label>
  <input type="text" class="form-control" name="employee_name" id="employee_name" value="{{ old('employee_name',$employee->employee_name) }}" required>
</div>
<div class="col-md-6">
  <label for="email" class="form-label" >Email</label>
  <input type="text" class="form-control" name="email" id="email" value="{{ old('email',$employee->email  ) }}" required>
</div>
<div class="col-md-4">
  <label for="company_id" class="form-label" >Empresa Titular</label>
    <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="company_id" id="company_id" value="{{old('company_id',$employee->company_id)}}">
      <option value="" {{ empty($selectedCompanyId) ? 'selected' : '' }}>Seleccione una Empresa</option>
      @foreach ( $companies as $company )
        <option value="{{$company->id}}" @if ($company->id == old('company_id',$employee->company_id)) selected @endif>{{$company->company_name}}</option>
      @endforeach
      <option value="__new__" {{ $isCreatingCompany ? 'selected' : '' }}>+ Crear nueva empresa</option>
    </select>
    <input
      type="text"
      class="form-control {{ $errors->has('new_company_name') ? 'is-invalid' : '' }}"
      name="new_company_name"
      id="new_company_name"
      value="{{ old('new_company_name') }}"
      placeholder="Nombre de la nueva empresa"
      style="{{ $isCreatingCompany ? '' : 'display:none;' }}"
    >
    @error('new_company_name')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    @error('company_id')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
<div class="col-md-4">
  <label for="department_id" class="form-label" >Departamento Asignado</label>
  <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="department_id" id="department_id" value="{{old('company_id',$employee->department_id)}}">
    <option selected>Seleccione un departmamento</option>
    @foreach ( $departments as $department )
        <option value="{{$department->id}}" @if ($department->id==old('department_id',$employee->department_id)) selected @endif>{{$department->department_name}}</option>
    @endforeach
  </select>
</div>
<div class="col-md-4">
  <label for="job_title" class="form-label" >Cargo</label>
  <input type="text" class="form-control" name="job_title" id="job_title" value="{{ old('job_title',$employee->job_title  ) }}" required>
</div>
<br>
<div class="col-6 m3">
  <button type="submit" class="btn btn-primary">{{$btnText}}</button>
</div>

@once
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var companySelect = document.getElementById('company_id');
        var newCompanyInput = document.getElementById('new_company_name');

        if (!companySelect || !newCompanyInput) {
          return;
        }

        function toggleNewCompanyField() {
          var shouldShow = companySelect.value === '__new__';
          newCompanyInput.style.display = shouldShow ? '' : 'none';

          if (shouldShow) {
            newCompanyInput.setAttribute('required', 'required');
          } else {
            newCompanyInput.removeAttribute('required');
            newCompanyInput.value = '';
          }
        }

        companySelect.addEventListener('change', toggleNewCompanyField);
        toggleNewCompanyField();
      });
    </script>
  @endpush
@endonce

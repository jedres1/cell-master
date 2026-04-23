@csrf

        <div class="col-md-6">
          <label for="cellphone_id" class="form-label" >Celular</label>
          <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="cellphone_id" id="cellphone_id" value="{{old('cellphone_id',$assignment->cellphone_id)}}">
            <option selected>Seleccione Celular</option>
            @foreach ( $cellphones as $cellphone )
                <option value="{{$cellphone->id}}" @if ($cellphone->id == old('cellphone_id',$assignment->cellphone_id))selected @endif>{{ $cellphone->brand.' / '.$cellphone->model.' / '.$cellphone->company->company_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label for="employee_id" class="form-label" >Empleado Asignado</label>
          <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="employee_id" id="employee_id"  value="{{old('employeed_id',$assignment->employeed_id)}}">
            @foreach ( $employees as $employee )
                <option value="{{$employee->id}}" @if ($employee->id == old('employee_id',$assignment->employee_id))selected @endif>{{$employee->employee_name.' / '.$employee->company->company_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label for="status" class="form-label" >Estatus Asignacion</label>
          <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="status" id="status">
            <option value="1" @if ($assignment->status == 1) selected @endif>Entrega Pendiente</option>
            <option value="2" @if ($assignment->status == 2) selected @endif>Activo</option>
            <option value="3" @if ($assignment->status == 3) selected @endif>Inactivo</option>
          </select>
        </div>
        <div class="form-group col-12 m3">
          <label for="note" class="form-label" >Nota</label>
          <input type="text" class="form-control" name="note" id="note" value="{{ old('note',$assignment->note) }}" required>
        </div>
        <br>
        <div class="col-6 m3">
          <button type="submit" class="btn btn-primary">{{$btnText}}</button>
        </div>
        

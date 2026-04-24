@php
  $selectedEmployeeId = old('employee_id', optional($selectedEmployee)->id);
  $selectedCellphoneId = old('cellphone_id', optional($selectedCellphone)->id);
  $selectedNumberId = old('number_id', optional($selectedNumber)->id);
@endphp

<style>
  .assignment-shell {
    width: 100%;
    max-width: none;
    margin: 0;
  }

  .assignment-layout {
    display: grid;
    grid-template-columns: minmax(0, 70%) minmax(0, 30%);
    gap: 1.5rem;
    align-items: start;
  }

  .assignment-intro-card,
  .assignment-builder-panel {
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid #dfe8f3;
    border-radius: 1rem;
    box-shadow: 0 14px 40px rgba(32, 63, 105, 0.08);
  }

  .assignment-intro-card {
    padding: 1rem 1.1rem;
  }

  .assignment-main-column,
  .assignment-sidebar {
    display: grid;
    gap: 1.5rem;
  }

  .assignment-sidebar {
    position: sticky;
    top: 1rem;
  }

  .assignment-intro {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
  }

  .assignment-intro-card__step {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #3983d5;
    margin-bottom: 0.55rem;
  }

  .assignment-selection-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.5rem;
    align-items: start;
  }

  .assignment-library-group {
    overflow: hidden;
    background: #fcfdff;
    border-color: #e6edf5;
    box-shadow: 0 10px 24px rgba(32, 63, 105, 0.05);
  }

  .assignment-library-group__header {
    margin-bottom: 1rem;
  }

  .assignment-library-column {
    min-width: 0;
    padding: 0 0.2rem;
    border-right: 1px solid #edf2f7;
  }

  .assignment-library-column:last-child {
    border-right: 0;
  }

  .assignment-workbench-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 1.5rem;
  }

  .assignment-section-title {
    font-size: 0.75rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-weight: 700;
    color: #587394;
    margin-bottom: 0.75rem;
  }

  .assignment-panel-note {
    border-radius: 0.9rem;
    padding: 0.9rem 1rem;
    background: #f5f9fd;
    border: 1px solid #dfe8f3;
    color: #536b88;
    margin-bottom: 1rem;
  }

  .assignment-library-header {
    display: flex;
    justify-content: space-between;
    gap: 0.75rem;
    align-items: center;
    margin-bottom: 0.45rem;
  }

  .assignment-library-title {
    margin: 0;
    font-size: 0.82rem;
    font-weight: 700;
    color: #23364d;
  }

  .assignment-library-meta {
    display: none;
  }

  .assignment-search {
    margin-bottom: 0.45rem;
  }

  .assignment-search .input-group-text,
  .assignment-search .form-control {
    border-color: #e6edf5;
    background: #fff;
    font-size: 0.78rem;
    min-height: 36px;
    padding-top: 0.45rem;
    padding-bottom: 0.45rem;
  }

  .assignment-pool {
    display: grid;
    gap: 0.35rem;
    max-height: 12rem;
    overflow: auto;
    padding-right: 0.25rem;
  }

  .assignment-slot-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
  }

  .assignment-card {
    border: 1px solid #edf2f7;
    border-radius: 0.65rem;
    padding: 0.55rem 0.65rem;
    background: #ffffff;
    cursor: grab;
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease, background 0.15s ease;
  }

  .assignment-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(51, 92, 149, 0.05);
    border-color: #d7e4f0;
  }

  .assignment-card.is-selected {
    border-color: #3983d5;
    box-shadow: 0 6px 14px rgba(57, 131, 213, 0.08);
    background: #f7fbff;
  }

  .assignment-card.is-hidden {
    display: none;
  }

  .assignment-card__tag {
    display: none;
  }

  .assignment-card__tag--employee { background: #dff3ff; color: #0a6aa1; }
  .assignment-card__tag--cellphone { background: #e8e3ff; color: #5641c9; }
  .assignment-card__tag--number { background: #def7ec; color: #19744f; }

  .assignment-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.35rem;
  }

  .assignment-card__hint {
    font-size: 0.64rem;
    color: #8a9aae;
  }

  .assignment-card__title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #23364d;
    margin-bottom: 0.05rem;
    line-height: 1.35;
  }

  .assignment-card__subtitle {
    font-size: 0.7rem;
    color: #6c7f95;
    margin-bottom: 0.05rem;
  }

  .assignment-card__meta {
    font-size: 0.64rem;
    color: #8a9aae;
    margin-bottom: 0;
    line-height: 1.35;
  }

  .assignment-empty-search {
    display: none;
    padding: 0.75rem 0.85rem;
    border: 1px dashed #c9d7e6;
    border-radius: 0.85rem;
    background: #fbfdff;
    color: #70859e;
    font-size: 0.78rem;
  }

  .assignment-slot {
    border: 2px dashed #bfd2e6;
    border-radius: 1rem;
    padding: 1.15rem;
    min-height: 190px;
    background: #fbfdff;
    transition: border-color 0.15s ease, background 0.15s ease;
  }

  .assignment-workbench-panel {
    background: linear-gradient(180deg, #ffffff 0%, #f5faff 100%);
    border: 1px solid #d7e5f2;
    box-shadow: 0 18px 45px rgba(32, 63, 105, 0.1);
  }

  .assignment-workbench-panel .assignment-section-title {
    text-align: left;
    font-size: 0.82rem;
  }

  .assignment-workbench-panel .assignment-panel-note {
    margin-bottom: 1.25rem;
    text-align: left;
  }

  .assignment-slot.is-over {
    border-color: #3983d5;
    background: #eef6ff;
  }

  .assignment-slot.is-filled {
    border-style: solid;
    border-color: #8db4e2;
    background: #fff;
  }

  .assignment-slot.is-required {
    border-color: #e74c3c;
    background: #fff8f7;
  }

  .assignment-slot__label {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #5f7897;
    margin-bottom: 0.7rem;
  }

  .assignment-slot__empty {
    color: #7f94ad;
    font-size: 0.95rem;
    margin-bottom: 0;
  }

  .assignment-summary-list {
    display: grid;
    gap: 0.75rem;
  }

  .assignment-summary-item {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    align-items: center;
    padding: 0.85rem 0.95rem;
    border: 1px solid #e0e7ef;
    border-radius: 0.85rem;
    background: #fff;
  }

  .assignment-summary-item.is-muted {
    background: #f7f9fc;
  }

  .assignment-summary-item strong {
    display: block;
    font-size: 0.85rem;
    color: #587394;
    margin-bottom: 0.2rem;
  }

  .assignment-summary-item span {
    color: #23364d;
  }

  .assignment-actions {
    display: flex;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
  }

  .assignment-remove-btn {
    border: 0;
    background: #eef3f9;
    color: #31557c;
    border-radius: 999px;
    width: 32px;
    height: 32px;
    line-height: 32px;
    text-align: center;
  }

  @media (max-width: 991px) {
    .assignment-shell {
      max-width: 100%;
    }

    .assignment-layout,
    .assignment-intro,
    .assignment-selection-grid,
    .assignment-slot-grid {
      grid-template-columns: 1fr;
    }

    .assignment-sidebar {
      position: static;
    }

    .assignment-builder-panel {
      padding: 1.25rem !important;
    }

    .assignment-slot {
      min-height: auto;
    }

    .assignment-pool {
      max-height: none;
      overflow: visible;
      padding-right: 0;
    }
  }

  @media (max-width: 575px) {
    .assignment-shell {
      max-width: 100%;
    }

    .assignment-library-header,
    .assignment-summary-item,
    .assignment-card__footer {
      flex-direction: column;
      align-items: flex-start;
    }

    .assignment-actions .btn {
      width: 100%;
    }
  }
</style>

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="assignment-shell">
  <form action="{{ $action }}" method="POST" id="assignment-builder-form">
    @csrf
    @if ($method !== 'POST')
      @method($method)
    @endif

    <input type="hidden" name="employee_id" id="assignment-employee-id" value="{{ $selectedEmployeeId }}">
    <input type="hidden" name="cellphone_id" id="assignment-cellphone-id" value="{{ $selectedCellphoneId }}">
    <input type="hidden" name="number_id" id="assignment-number-id" value="{{ $selectedNumberId }}">

    <div class="assignment-layout">
      <div class="assignment-main-column">
        <div class="assignment-intro">
          <div class="assignment-intro-card">
            <span class="assignment-intro-card__step">Paso 1</span>
            <div class="font-weight-bold mb-1">Arma la asignacion</div>
            <div class="text-muted small">La mesa queda al frente para que visualices rapido la combinacion actual.</div>
          </div>
          <div class="assignment-intro-card">
            <span class="assignment-intro-card__step">Paso 2</span>
            <div class="font-weight-bold mb-1">Completa al menos 2 objetos</div>
            <div class="text-muted small">Puedes combinar empleado, celular y numero; lo ideal es completar los 3.</div>
          </div>
          <div class="assignment-intro-card">
            <span class="assignment-intro-card__step">Paso 3</span>
            <div class="font-weight-bold mb-1">Guarda el estatus</div>
            <div class="text-muted small">Define el estado y agrega una nota breve para dejar contexto adicional.</div>
          </div>
        </div>

        <div class="assignment-workbench-grid">
          <div class="assignment-builder-panel assignment-workbench-panel p-4">
            <div class="assignment-section-title">Mesa de Asignacion</div>
            <div class="assignment-panel-note">
              Revisa aqui lo seleccionado. Si necesitas cambiar algo, elige otro elemento del mismo tipo desde el panel derecho o usa la "x" para quitarlo.
            </div>

            <div class="assignment-slot-grid">
              <div class="assignment-slot" data-slot="employee">
                <div class="assignment-slot__label">Empleado</div>
                <div class="assignment-slot__content"></div>
              </div>
              <div class="assignment-slot" data-slot="cellphone">
                <div class="assignment-slot__label">Celular</div>
                <div class="assignment-slot__content"></div>
              </div>
              <div class="assignment-slot" data-slot="number">
                <div class="assignment-slot__label">Numero</div>
                <div class="assignment-slot__content"></div>
              </div>
            </div>
          </div>

          <div class="assignment-builder-panel p-4">
            <div class="assignment-section-title">Resumen</div>
            <div class="assignment-summary-list mb-4">
              <div class="assignment-summary-item">
                <div>
                  <strong>Combinacion actual</strong>
                  <span id="assignment-summary-combination">Selecciona al menos dos objetos</span>
                </div>
                <span class="badge badge-primary" id="assignment-summary-count">0/3</span>
              </div>
              <div class="assignment-summary-item is-muted">
                <div>
                  <strong>Listo para guardar</strong>
                  <span id="assignment-summary-status">Aun falta seleccionar al menos 2 objetos.</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="status" class="form-label">Estatus Asignacion</label>
              <select class="form-control" name="status" id="status">
                <option value="1" @if (old('status', $assignment->status) == 1) selected @endif>Entrega Pendiente</option>
                <option value="2" @if (old('status', $assignment->status) == 2) selected @endif>Activo</option>
                <option value="3" @if (old('status', $assignment->status) == 3) selected @endif>Inactivo</option>
              </select>
            </div>

            <div class="form-group">
              <label for="note" class="form-label">Nota</label>
              <input type="text" class="form-control" name="note" id="note" value="{{ old('note', $assignment->note) }}" placeholder="Ejemplo: equipo entregado con cargador">
            </div>

            <div class="assignment-actions">
              <a href="{{ $method === 'POST' ? url('/assignments') : url('/assignments/show', $assignment) }}" class="btn btn-warning">Cancelar</a>
              <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
            </div>
          </div>
        </div>
      </div>

      <div class="assignment-sidebar">
        <div class="assignment-builder-panel assignment-library-group p-4">
          <div class="assignment-library-group__header">
            <div class="assignment-section-title mb-0">Objetos disponibles</div>
          </div>

          <div class="assignment-selection-grid">
            <div class="assignment-library-column" data-library-section="employee">
              <div class="assignment-library-header">
                <div>
                  <h3 class="assignment-library-title">Empleados</h3>
                  <div class="assignment-library-meta">Selecciona a la persona responsable del equipo.</div>
                </div>
                <span class="badge badge-light" data-results-counter="employee">{{ $employees->count() }} visibles</span>
              </div>
              <div class="assignment-search">
                <div class="input-group input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                  </div>
                  <input type="text" class="form-control assignment-filter-input" data-filter-type="employee" placeholder="Buscar empleado, cargo, empresa o departamento">
                </div>
              </div>
              <div class="assignment-pool">
                @foreach ($employees as $employee)
                  <div class="assignment-card"
                       draggable="true"
                       data-type="employee"
                       data-id="{{ $employee->id }}"
                       data-title="{{ $employee->employee_name }}"
                       data-subtitle="{{ $employee->job_title }}"
                       data-meta="Empresa: {{ $employee->company->company_name }} | Departamento: {{ $employee->department->department_name }}"
                       data-search="{{ strtolower($employee->employee_name.' '.$employee->job_title.' '.$employee->company->company_name.' '.$employee->department->department_name) }}">
                    <span class="assignment-card__tag assignment-card__tag--employee">Empleado</span>
                    <div class="assignment-card__title">{{ $employee->employee_name }}</div>
                    <div class="assignment-card__subtitle">{{ $employee->job_title }}</div>
                    <p class="assignment-card__meta">{{ $employee->company->company_name }} | {{ $employee->department->department_name }}</p>
                    <div class="assignment-card__footer">
                      <span class="assignment-card__hint">Arrastra o toca usar</span>
                      <button type="button" class="btn btn-sm btn-outline-primary" data-assign-card>Usar</button>
                    </div>
                  </div>
                @endforeach
                <div class="assignment-empty-search" data-empty-state="employee">
                  No hay empleados que coincidan con la busqueda.
                </div>
              </div>
            </div>

            <div class="assignment-library-column" data-library-section="cellphone">
              <div class="assignment-library-header">
                <div>
                  <h3 class="assignment-library-title">Celulares</h3>
                  <div class="assignment-library-meta">Solo se muestran equipos sin una asignacion activa.</div>
                </div>
                <span class="badge badge-light" data-results-counter="cellphone">{{ $cellphones->count() }} visibles</span>
              </div>
              <div class="assignment-search">
                <div class="input-group input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                  </div>
                  <input type="text" class="form-control assignment-filter-input" data-filter-type="cellphone" placeholder="Buscar marca, modelo, imei, empresa o departamento">
                </div>
              </div>
              <div class="assignment-pool">
                @foreach ($cellphones as $cellphone)
                  <div class="assignment-card"
                       draggable="true"
                       data-type="cellphone"
                       data-id="{{ $cellphone->id }}"
                       data-title="{{ $cellphone->brand }} {{ $cellphone->model }}"
                       data-subtitle="IMEI: {{ $cellphone->imei }}"
                       data-meta="Empresa: {{ $cellphone->company->company_name }} | Departamento: {{ $cellphone->department->department_name }}"
                       data-search="{{ strtolower($cellphone->brand.' '.$cellphone->model.' '.$cellphone->imei.' '.$cellphone->company->company_name.' '.$cellphone->department->department_name) }}">
                    <span class="assignment-card__tag assignment-card__tag--cellphone">Celular</span>
                    <div class="assignment-card__title">{{ $cellphone->brand }} {{ $cellphone->model }}</div>
                    <div class="assignment-card__subtitle">IMEI: {{ $cellphone->imei }}</div>
                    <p class="assignment-card__meta">{{ $cellphone->company->company_name }} | {{ $cellphone->department->department_name }}</p>
                    <div class="assignment-card__footer">
                      <span class="assignment-card__hint">Arrastra o toca usar</span>
                      <button type="button" class="btn btn-sm btn-outline-primary" data-assign-card>Usar</button>
                    </div>
                  </div>
                @endforeach
                <div class="assignment-empty-search" data-empty-state="cellphone">
                  No hay celulares que coincidan con la busqueda.
                </div>
              </div>
            </div>

            <div class="assignment-library-column" data-library-section="number">
              <div class="assignment-library-header">
                <div>
                  <h3 class="assignment-library-title">Numeros</h3>
                  <div class="assignment-library-meta">Puedes dejar este espacio vacio, pero la asignacion debe tener al menos 2 objetos.</div>
                </div>
                <span class="badge badge-light" data-results-counter="number">{{ $numbers->count() }} visibles</span>
              </div>
              <div class="assignment-search">
                <div class="input-group input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                  </div>
                  <input type="text" class="form-control assignment-filter-input" data-filter-type="number" placeholder="Buscar numero, plan o empresa">
                </div>
              </div>
              <div class="assignment-pool">
                @foreach ($numbers as $number)
                  <div class="assignment-card"
                       draggable="true"
                       data-type="number"
                       data-id="{{ $number->id }}"
                       data-title="{{ $number->number }}"
                       data-subtitle="Plan: {{ $number->data_plan ?: 'Sin plan' }}"
                       data-meta="Empresa: {{ $number->company->company_name }}"
                       data-search="{{ strtolower($number->number.' '.($number->data_plan ?: 'sin plan').' '.$number->company->company_name) }}">
                    <span class="assignment-card__tag assignment-card__tag--number">Numero</span>
                    <div class="assignment-card__title">{{ $number->number }}</div>
                    <div class="assignment-card__subtitle">Plan: {{ $number->data_plan ?: 'Sin plan' }}</div>
                    <p class="assignment-card__meta">{{ $number->company->company_name }}</p>
                    <div class="assignment-card__footer">
                      <span class="assignment-card__hint">Arrastra o toca usar</span>
                      <button type="button" class="btn btn-sm btn-outline-primary" data-assign-card>Usar</button>
                    </div>
                  </div>
                @endforeach
                <div class="assignment-empty-search" data-empty-state="number">
                  No hay numeros que coincidan con la busqueda.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

@php
  $preloadedCards = [];
  if ($selectedEmployee) {
      $preloadedCards['employee'] = [
          'type' => 'employee',
          'id' => $selectedEmployee->id,
          'title' => $selectedEmployee->employee_name,
          'subtitle' => $selectedEmployee->job_title,
          'meta' => 'Empresa: '.$selectedEmployee->company->company_name.' | Departamento: '.$selectedEmployee->department->department_name,
      ];
  }
  if ($selectedCellphone) {
      $preloadedCards['cellphone'] = [
          'type' => 'cellphone',
          'id' => $selectedCellphone->id,
          'title' => $selectedCellphone->brand.' '.$selectedCellphone->model,
          'subtitle' => 'IMEI: '.$selectedCellphone->imei,
          'meta' => 'Empresa: '.$selectedCellphone->company->company_name.' | Departamento: '.$selectedCellphone->department->department_name,
      ];
  }
  if ($selectedNumber) {
      $preloadedCards['number'] = [
          'type' => 'number',
          'id' => $selectedNumber->id,
          'title' => $selectedNumber->number,
          'subtitle' => 'Plan: '.($selectedNumber->data_plan ?: 'Sin plan'),
          'meta' => 'Empresa: '.$selectedNumber->company->company_name,
      ];
  }
@endphp

@section('scripts')
<script>
  (function () {
    var preloadedCards = @json($preloadedCards);
    var slotInputs = {
      employee: document.getElementById('assignment-employee-id'),
      cellphone: document.getElementById('assignment-cellphone-id'),
      number: document.getElementById('assignment-number-id')
    };

    var slotElements = document.querySelectorAll('.assignment-slot');
    var summaryCombination = document.getElementById('assignment-summary-combination');
    var summaryCount = document.getElementById('assignment-summary-count');
    var summaryStatus = document.getElementById('assignment-summary-status');
    var filterInputs = document.querySelectorAll('.assignment-filter-input');

    function renderEmptyState(slot) {
      slot.classList.remove('is-filled');
      slot.classList.remove('is-required');
      slot.querySelector('.assignment-slot__content').innerHTML = '<p class="assignment-slot__empty">Arrastra aqui una tarjeta de ' + slot.dataset.slot + '.</p>';
    }

    function tagClass(type) {
      if (type === 'employee') return 'assignment-card__tag--employee';
      if (type === 'cellphone') return 'assignment-card__tag--cellphone';
      return 'assignment-card__tag--number';
    }

    function prettyType(type) {
      if (type === 'employee') return 'Empleado';
      if (type === 'cellphone') return 'Celular';
      return 'Numero';
    }

    function syncSelectedCards() {
      document.querySelectorAll('.assignment-card').forEach(function (card) {
        var input = slotInputs[card.dataset.type];
        var isSelected = input && input.value === card.dataset.id;
        card.classList.toggle('is-selected', isSelected);
      });
    }

    function updateSummary() {
      var parts = [];

      ['employee', 'cellphone', 'number'].forEach(function (slotName) {
        if (slotInputs[slotName].value) {
          parts.push(prettyType(slotName));
        }
      });

      summaryCombination.textContent = parts.length ? parts.join(' + ') : 'Selecciona al menos dos objetos';
      summaryCount.textContent = parts.length + '/3';
      summaryStatus.textContent = parts.length >= 2
        ? 'La asignacion ya cumple el minimo requerido y se puede guardar.'
        : 'Aun falta seleccionar al menos 2 objetos.';
    }

    function renderCard(slotName, cardData) {
      var slot = document.querySelector('.assignment-slot[data-slot="' + slotName + '"]');
      if (!slot) return;

      slot.classList.add('is-filled');
      slot.classList.remove('is-required');
      slot.querySelector('.assignment-slot__content').innerHTML =
        '<div>' +
          '<span class="assignment-card__tag ' + tagClass(cardData.type) + '">' + prettyType(cardData.type) + '</span>' +
          '<h4 class="h5 mb-1">' + cardData.title + '</h4>' +
          '<p class="mb-1 text-muted">' + (cardData.subtitle || '') + '</p>' +
          '<p class="mb-3 small">' + (cardData.meta || '') + '</p>' +
          '<button type="button" class="assignment-remove-btn" data-remove-slot="' + slotName + '">&times;</button>' +
        '</div>';

      slotInputs[slotName].value = cardData.id;
      syncSelectedCards();
      updateSummary();
    }

    function hydrateFromElement(slotName, element) {
      renderCard(slotName, {
        type: element.dataset.type,
        id: element.dataset.id,
        title: element.dataset.title,
        subtitle: element.dataset.subtitle,
        meta: element.dataset.meta
      });
    }

    function clearSlot(slotName) {
      slotInputs[slotName].value = '';
      renderEmptyState(document.querySelector('.assignment-slot[data-slot="' + slotName + '"]'));
      syncSelectedCards();
      updateSummary();
    }

    function filterCards(type, query) {
      var normalizedQuery = (query || '').toLowerCase().trim();
      var visibleCount = 0;

      document.querySelectorAll('.assignment-card[data-type="' + type + '"]').forEach(function (card) {
        var matches = !normalizedQuery || (card.dataset.search || '').indexOf(normalizedQuery) !== -1;
        card.classList.toggle('is-hidden', !matches);

        if (matches) {
          visibleCount += 1;
        }
      });

      var emptyState = document.querySelector('[data-empty-state="' + type + '"]');
      var counter = document.querySelector('[data-results-counter="' + type + '"]');

      if (emptyState) {
        emptyState.style.display = visibleCount ? 'none' : 'block';
      }

      if (counter) {
        counter.textContent = visibleCount + ' visibles';
      }
    }

    function validateSelection() {
      var selectedCount = ['employee', 'cellphone', 'number'].filter(function (slotName) {
        return !!slotInputs[slotName].value;
      }).length;

      slotElements.forEach(function (slot) {
        if (selectedCount < 2 && !slotInputs[slot.dataset.slot].value) {
          slot.classList.add('is-required');
        } else {
          slot.classList.remove('is-required');
        }
      });

      return selectedCount >= 2;
    }

    document.querySelectorAll('.assignment-card').forEach(function (card) {
      card.addEventListener('dragstart', function (event) {
        event.dataTransfer.setData('application/json', JSON.stringify({
          type: card.dataset.type,
          id: card.dataset.id,
          title: card.dataset.title,
          subtitle: card.dataset.subtitle,
          meta: card.dataset.meta
        }));
      });

      card.addEventListener('dblclick', function () {
        hydrateFromElement(card.dataset.type, card);
      });

      var assignButton = card.querySelector('[data-assign-card]');
      if (assignButton) {
        assignButton.addEventListener('click', function () {
          hydrateFromElement(card.dataset.type, card);
        });
      }
    });

    slotElements.forEach(function (slot) {
      renderEmptyState(slot);

      slot.addEventListener('dragover', function (event) {
        event.preventDefault();
        slot.classList.add('is-over');
      });

      slot.addEventListener('dragleave', function () {
        slot.classList.remove('is-over');
      });

      slot.addEventListener('drop', function (event) {
        event.preventDefault();
        slot.classList.remove('is-over');

        var raw = event.dataTransfer.getData('application/json');
        if (!raw) {
          return;
        }

        var data = JSON.parse(raw);
        if (data.type !== slot.dataset.slot) {
          return;
        }

        renderCard(slot.dataset.slot, data);
      });
    });

    filterInputs.forEach(function (input) {
      filterCards(input.dataset.filterType, input.value);

      input.addEventListener('input', function () {
        filterCards(input.dataset.filterType, input.value);
      });
    });

    document.addEventListener('click', function (event) {
      if (!event.target.matches('[data-remove-slot]')) {
        return;
      }

      clearSlot(event.target.dataset.removeSlot);
    });

    Object.keys(preloadedCards).forEach(function (slotName) {
      renderCard(slotName, preloadedCards[slotName]);
    });

    ['employee', 'cellphone', 'number'].forEach(function (slotName) {
      if (slotInputs[slotName].value && !preloadedCards[slotName]) {
        var fallbackCard = document.querySelector('.assignment-card[data-type="' + slotName + '"][data-id="' + slotInputs[slotName].value + '"]');
        if (fallbackCard) {
          hydrateFromElement(slotName, fallbackCard);
        }
      }
    });

    syncSelectedCards();
    updateSummary();
    validateSelection();

    document.getElementById('assignment-builder-form').addEventListener('submit', function (event) {
      if (!validateSelection()) {
        event.preventDefault();
        alert('La asignacion debe contener al menos dos objetos.');
      }
    });
  })();
</script>
@endsection

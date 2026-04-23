@php
  $selectedEmployeeId = old('employee_id', optional($selectedEmployee)->id);
  $selectedCellphoneId = old('cellphone_id', optional($selectedCellphone)->id);
  $selectedNumberId = old('number_id', optional($selectedNumber)->id);
@endphp

<style>
  .assignment-shell {
    max-width: 1320px;
    margin: 0 auto;
  }

  .assignment-board {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.9fr);
    gap: 1.5rem;
    align-items: start;
  }

  .assignment-builder-panel {
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid #dfe8f3;
    border-radius: 1rem;
    box-shadow: 0 14px 40px rgba(32, 63, 105, 0.08);
  }

  .assignment-section-title {
    font-size: 0.75rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-weight: 700;
    color: #587394;
    margin-bottom: 1rem;
  }

  .assignment-pool {
    display: grid;
    gap: 0.85rem;
    max-height: 62vh;
    overflow: auto;
    padding-right: 0.25rem;
  }

  .assignment-card {
    border: 1px solid #d8e5f2;
    border-radius: 0.9rem;
    padding: 1rem;
    background: #fff;
    cursor: grab;
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
  }

  .assignment-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(51, 92, 149, 0.12);
    border-color: #8db4e2;
  }

  .assignment-card__tag {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    margin-bottom: 0.8rem;
  }

  .assignment-card__tag--employee { background: #dff3ff; color: #0a6aa1; }
  .assignment-card__tag--cellphone { background: #e8e3ff; color: #5641c9; }
  .assignment-card__tag--number { background: #def7ec; color: #19744f; }

  .assignment-slot {
    border: 2px dashed #bfd2e6;
    border-radius: 1rem;
    padding: 1rem;
    min-height: 160px;
    background: #fbfdff;
    transition: border-color 0.15s ease, background 0.15s ease;
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
      max-width: 720px;
    }

    .assignment-board {
      grid-template-columns: 1fr;
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

    .assignment-summary-item {
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

  <div class="assignment-board">
    <div class="assignment-builder-panel p-4">
      <div class="assignment-section-title">Biblioteca de Objetos</div>
      <div class="accordion" id="assignmentLibrary">
        <div class="card border-0 shadow-none">
          <div class="card-header bg-white px-0" id="headingEmployees">
            <button class="btn btn-link p-0 text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseEmployees" aria-expanded="true" aria-controls="collapseEmployees">
              Empleados
            </button>
          </div>
          <div id="collapseEmployees" class="collapse show" aria-labelledby="headingEmployees" data-parent="#assignmentLibrary">
            <div class="assignment-pool mb-4">
              @foreach ($employees as $employee)
                <div class="assignment-card"
                     draggable="true"
                     data-type="employee"
                     data-id="{{ $employee->id }}"
                     data-title="{{ $employee->employee_name }}"
                     data-subtitle="{{ $employee->job_title }}"
                     data-meta="Empresa: {{ $employee->company->company_name }} | Departamento: {{ $employee->department->department_name }}">
                  <span class="assignment-card__tag assignment-card__tag--employee">Empleado</span>
                  <h4 class="h5 mb-1">{{ $employee->employee_name }}</h4>
                  <p class="mb-1 text-muted">{{ $employee->job_title }}</p>
                  <p class="mb-0 small">Empresa: {{ $employee->company->company_name }}</p>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-none">
          <div class="card-header bg-white px-0" id="headingCellphones">
            <button class="btn btn-link p-0 text-left font-weight-bold collapsed" type="button" data-toggle="collapse" data-target="#collapseCellphones" aria-expanded="false" aria-controls="collapseCellphones">
              Celulares Disponibles
            </button>
          </div>
          <div id="collapseCellphones" class="collapse" aria-labelledby="headingCellphones" data-parent="#assignmentLibrary">
            <div class="assignment-pool mb-4">
              @foreach ($cellphones as $cellphone)
                <div class="assignment-card"
                     draggable="true"
                     data-type="cellphone"
                     data-id="{{ $cellphone->id }}"
                     data-title="{{ $cellphone->brand }} {{ $cellphone->model }}"
                     data-subtitle="IMEI: {{ $cellphone->imei }}"
                     data-meta="Empresa: {{ $cellphone->company->company_name }} | Departamento: {{ $cellphone->department->department_name }}">
                  <span class="assignment-card__tag assignment-card__tag--cellphone">Celular</span>
                  <h4 class="h5 mb-1">{{ $cellphone->brand }} {{ $cellphone->model }}</h4>
                  <p class="mb-1 text-muted">IMEI: {{ $cellphone->imei }}</p>
                  <p class="mb-0 small">Empresa: {{ $cellphone->company->company_name }}</p>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-none">
          <div class="card-header bg-white px-0" id="headingNumbers">
            <button class="btn btn-link p-0 text-left font-weight-bold collapsed" type="button" data-toggle="collapse" data-target="#collapseNumbers" aria-expanded="false" aria-controls="collapseNumbers">
              Numeros Disponibles
            </button>
          </div>
          <div id="collapseNumbers" class="collapse" aria-labelledby="headingNumbers" data-parent="#assignmentLibrary">
            <div class="assignment-pool">
              @foreach ($numbers as $number)
                <div class="assignment-card"
                     draggable="true"
                     data-type="number"
                     data-id="{{ $number->id }}"
                     data-title="{{ $number->number }}"
                     data-subtitle="Plan: {{ $number->data_plan ?: 'Sin plan' }}"
                     data-meta="Empresa: {{ $number->company->company_name }}">
                  <span class="assignment-card__tag assignment-card__tag--number">Numero</span>
                  <h4 class="h5 mb-1">{{ $number->number }}</h4>
                  <p class="mb-1 text-muted">Plan: {{ $number->data_plan ?: 'Sin plan' }}</p>
                  <p class="mb-0 small">Empresa: {{ $number->company->company_name }}</p>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="assignment-builder-panel p-4">
      <div class="assignment-section-title">Mesa de Asignacion</div>
      <div class="assignment-slot mb-3" data-slot="employee">
        <div class="assignment-slot__label">Empleado</div>
        <div class="assignment-slot__content"></div>
      </div>
      <div class="assignment-slot mb-3" data-slot="cellphone">
        <div class="assignment-slot__label">Celular</div>
        <div class="assignment-slot__content"></div>
      </div>
      <div class="assignment-slot mb-4" data-slot="number">
        <div class="assignment-slot__label">Numero</div>
        <div class="assignment-slot__content"></div>
      </div>

      <div class="assignment-section-title">Resumen</div>
      <div class="assignment-summary-list mb-4">
        <div class="assignment-summary-item">
          <div>
            <strong>Combinacion actual</strong>
            <span id="assignment-summary-combination">Selecciona al menos dos objetos</span>
          </div>
          <span class="badge badge-primary" id="assignment-summary-count">0/3</span>
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
        <input type="text" class="form-control" name="note" id="note" value="{{ old('note', $assignment->note) }}">
      </div>

      <div class="assignment-actions">
        <a href="{{ $method === 'POST' ? url('/assignments') : url('/assignments/show', $assignment) }}" class="btn btn-warning">Cancelar</a>
        <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
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

    function renderEmptyState(slot) {
      slot.classList.remove('is-filled');
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

    function renderCard(slotName, cardData) {
      var slot = document.querySelector('.assignment-slot[data-slot="' + slotName + '"]');
      if (!slot) return;

      slot.classList.add('is-filled');
      slot.querySelector('.assignment-slot__content').innerHTML =
        '<div>' +
          '<span class="assignment-card__tag ' + tagClass(cardData.type) + '">' + prettyType(cardData.type) + '</span>' +
          '<h4 class="h5 mb-1">' + cardData.title + '</h4>' +
          '<p class="mb-1 text-muted">' + (cardData.subtitle || '') + '</p>' +
          '<p class="mb-3 small">' + (cardData.meta || '') + '</p>' +
          '<button type="button" class="assignment-remove-btn" data-remove-slot="' + slotName + '">&times;</button>' +
        '</div>';

      slotInputs[slotName].value = cardData.id;

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
      updateSummary();
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
        var data = JSON.parse(event.dataTransfer.getData('application/json'));

        if (data.type !== slot.dataset.slot) {
          return;
        }

        renderCard(slot.dataset.slot, data);
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

    document.getElementById('assignment-builder-form').addEventListener('submit', function (event) {
      var selectedCount = ['employee', 'cellphone', 'number'].filter(function (slotName) {
        return !!slotInputs[slotName].value;
      }).length;

      if (selectedCount < 2) {
        event.preventDefault();
        alert('La asignacion debe contener al menos dos objetos.');
      }
    });
  })();
</script>
@endsection

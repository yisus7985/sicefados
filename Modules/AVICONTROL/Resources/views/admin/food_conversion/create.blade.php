@extends('avicontrol::layouts.admin')

@section('title', 'Nuevo Registro de Conversión Alimenticia')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Nuevo Registro de Conversión Alimenticia
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.food_conversion.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('avicontrol.admin.food_conversion.store') }}" method="POST" id="formConversion">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="galpon_id">Galpón <span class="text-danger">*</span></label>
                                    <select name="galpon_id" id="galpon_id" class="form-control @error('galpon_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un galpón</option>
                                        @foreach($galpones ?? [] as $galpon)
                                            <option value="{{ $galpon->id }}" {{ old('galpon_id') == $galpon->id ? 'selected' : '' }}>
                                                {{ $galpon->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('galpon_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="periodo_tipo">Tipo de Período <span class="text-danger">*</span></label>
                                    <select name="periodo_tipo" id="periodo_tipo" class="form-control @error('periodo_tipo') is-invalid @enderror" required>
                                        <option value="">Seleccione el período</option>
                                        <option value="diario" {{ old('periodo_tipo') == 'diario' ? 'selected' : '' }}>Diario</option>
                                        <option value="semanal" {{ old('periodo_tipo') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                        <option value="mensual" {{ old('periodo_tipo') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                        <option value="acumulado" {{ old('periodo_tipo') == 'acumulado' ? 'selected' : '' }}>Acumulado</option>
                                    </select>
                                    @error('periodo_tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                           class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                           value="{{ old('fecha_inicio') }}" required>
                                    @error('fecha_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de Fin <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" 
                                           class="form-control @error('fecha_fin') is-invalid @enderror" 
                                           value="{{ old('fecha_fin') }}" required>
                                    @error('fecha_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_produccion">Tipo de Producción <span class="text-danger">*</span></label>
                                    <select name="tipo_produccion" id="tipo_produccion" class="form-control @error('tipo_produccion') is-invalid @enderror" required>
                                        <option value="">Seleccione el tipo de producción</option>
                                        <option value="huevo" {{ old('tipo_produccion') == 'huevo' ? 'selected' : '' }}>Huevo</option>
                                        <option value="carne" {{ old('tipo_produccion') == 'carne' ? 'selected' : '' }}>Carne</option>
                                        <option value="reproductor" {{ old('tipo_produccion') == 'reproductor' ? 'selected' : '' }}>Reproductor</option>
                                    </select>
                                    @error('tipo_produccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="responsable">Responsable</label>
                                    <input type="text" name="responsable" id="responsable" 
                                           class="form-control @error('responsable') is-invalid @enderror" 
                                           value="{{ old('responsable', auth()->user()->name ?? '') }}">
                                    @error('responsable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de cálculo automático -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-calculator"></i> Cálculo Automático
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    Puede calcular automáticamente la conversión alimenticia basándose en los registros de consumo y producción del período seleccionado.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" id="btn_calcular_automatico" class="btn btn-primary">
                                            <i class="fas fa-calculator"></i> Calcular Automáticamente
                                        </button>
                                        <small class="form-text text-muted">
                                            Esto calculará la conversión basándose en los datos existentes de consumo y producción.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de datos manuales -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-edit"></i> Datos Manuales
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_alimento_consumido">Total Alimento Consumido (kg)</label>
                                            <input type="number" name="total_alimento_consumido" id="total_alimento_consumido" 
                                                   class="form-control @error('total_alimento_consumido') is-invalid @enderror" 
                                                   value="{{ old('total_alimento_consumido') }}" step="0.01" min="0">
                                            @error('total_alimento_consumido')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_producto_obtenido">Total Producto Obtenido (kg)</label>
                                            <input type="number" name="total_producto_obtenido" id="total_producto_obtenido" 
                                                   class="form-control @error('total_producto_obtenido') is-invalid @enderror" 
                                                   value="{{ old('total_producto_obtenido') }}" step="0.01" min="0">
                                            @error('total_producto_obtenido')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="conversion_alimenticia">Conversión Alimenticia</label>
                                            <input type="number" name="conversion_alimenticia" id="conversion_alimenticia" 
                                                   class="form-control @error('conversion_alimenticia') is-invalid @enderror" 
                                                   value="{{ old('conversion_alimenticia') }}" step="0.0001" min="0" readonly>
                                            @error('conversion_alimenticia')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Se calcula automáticamente: Alimento Consumido / Producto Obtenido
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Eficiencia</label>
                                            <div id="eficiencia_display" class="form-control-plaintext">
                                                <span class="badge badge-secondary">No calculado</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" 
                                      class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Registro
                            </button>
                            <a href="{{ route('avicontrol.admin.food_conversion.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Calcular conversión automáticamente cuando cambien los valores
    $('#total_alimento_consumido, #total_producto_obtenido').on('input', function() {
        calcularConversion();
    });

    // Botón para cálculo automático
    $('#btn_calcular_automatico').click(function() {
        calcularAutomaticamente();
    });

    // Validar fechas
    $('#fecha_inicio, #fecha_fin').on('change', function() {
        validarFechas();
    });

    function calcularConversion() {
        let alimento = parseFloat($('#total_alimento_consumido').val()) || 0;
        let producto = parseFloat($('#total_producto_obtenido').val()) || 0;
        
        if (producto > 0) {
            let conversion = alimento / producto;
            $('#conversion_alimenticia').val(conversion.toFixed(4));
            
            // Calcular eficiencia
            let eficiencia = '';
            let color = '';
            
            if (conversion <= 1.5) {
                eficiencia = 'Excelente';
                color = 'success';
            } else if (conversion <= 2.0) {
                eficiencia = 'Buena';
                color = 'info';
            } else if (conversion <= 2.5) {
                eficiencia = 'Regular';
                color = 'warning';
            } else {
                eficiencia = 'Baja';
                color = 'danger';
            }
            
            $('#eficiencia_display').html(`<span class="badge badge-${color}">${eficiencia}</span>`);
        } else {
            $('#conversion_alimenticia').val('');
            $('#eficiencia_display').html('<span class="badge badge-secondary">No calculado</span>');
        }
    }

    function calcularAutomaticamente() {
        let galponId = $('#galpon_id').val();
        let fechaInicio = $('#fecha_inicio').val();
        let fechaFin = $('#fecha_fin').val();
        let tipoProduccion = $('#tipo_produccion').val();

        if (!galponId || !fechaInicio || !fechaFin || !tipoProduccion) {
            alert('Por favor complete todos los campos requeridos antes de calcular automáticamente.');
            return;
        }

        // Mostrar loading
        $('#btn_calcular_automatico').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Calculando...');

        $.ajax({
            url: '{{ route("avicontrol.admin.food_conversion.calcular_automaticamente") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                galpon_id: galponId,
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin,
                tipo_produccion: tipoProduccion
            },
            success: function(response) {
                if (response.success) {
                    $('#total_alimento_consumido').val(response.data.total_alimento_consumido);
                    $('#total_producto_obtenido').val(response.data.total_producto_obtenido);
                    calcularConversion();
                    
                    // Mostrar mensaje de éxito
                    alert('Cálculo automático completado exitosamente.');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error al calcular automáticamente. Por favor, intente nuevamente.');
            },
            complete: function() {
                $('#btn_calcular_automatico').prop('disabled', false).html('<i class="fas fa-calculator"></i> Calcular Automáticamente');
            }
        });
    }

    function validarFechas() {
        let fechaInicio = $('#fecha_inicio').val();
        let fechaFin = $('#fecha_fin').val();
        
        if (fechaInicio && fechaFin) {
            if (fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor a la fecha de fin.');
                $('#fecha_fin').val('');
            }
        }
    }

    // Validación del formulario
    $('#formConversion').on('submit', function(e) {
        let galponId = $('#galpon_id').val();
        let periodoTipo = $('#periodo_tipo').val();
        let fechaInicio = $('#fecha_inicio').val();
        let fechaFin = $('#fecha_fin').val();
        let tipoProduccion = $('#tipo_produccion').val();
        let alimento = parseFloat($('#total_alimento_consumido').val()) || 0;
        let producto = parseFloat($('#total_producto_obtenido').val()) || 0;

        if (!galponId || !periodoTipo || !fechaInicio || !fechaFin || !tipoProduccion) {
            alert('Por favor complete todos los campos requeridos.');
            e.preventDefault();
            return false;
        }

        if (alimento <= 0 || producto <= 0) {
            alert('Los valores de alimento consumido y producto obtenido deben ser mayores a 0.');
            e.preventDefault();
            return false;
        }

        return true;
    });
});
</script>
@endpush

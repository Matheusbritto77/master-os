@extends('layouts.bar')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-calendar-event"></i> Agendamentos de WhatsApp</h4>
                    <button class="btn btn-primary" id="btnNovoAgendamento">
                        <i class="bi bi-plus-circle"></i> Novo Agendamento
                    </button>
                </div>
                <div class="card-body">
                    <div id="alert-area"></div>
                    <div id="agendamentos-lista">
                        <div class="text-center text-muted" id="loading-agendamentos">Carregando agendamentos...</div>
                        <table class="table table-bordered d-none" id="tabela-agendamentos">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Frequência</th>
                                    <th>Modo</th>
                                    <th>Horários/Período</th>
                                    <th>Grupos</th>
                                    <th>Dias Específicos</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo/Editar Agendamento -->
<div class="modal fade" id="modalAgendamento" tabindex="-1" aria-labelledby="modalAgendamentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formAgendamento" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgendamentoLabel"><i class="bi bi-calendar-plus"></i> Novo Agendamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div id="alert-modal-area"></div>
                    <input type="hidden" id="agendamento_id" name="agendamento_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="description" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="imagem" class="form-label">Imagem (opcional)</label>
                                <input type="file" class="form-control" id="imagem" name="image" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="grupos" class="form-label">
                                    Grupos
                                    <div class="spinner-border spinner-border-sm text-primary d-none" id="loading-grupos" role="status">
                                        <span class="visually-hidden">Carregando...</span>
                                    </div>
                                </label>
                                <div id="grupos-lista" class="d-flex flex-wrap gap-2">
                                    <div class="text-muted small">Carregando grupos...</div>
                                </div>
                                <input type="hidden" name="group_ids" id="group_ids">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="frequencia" class="form-label">Frequência</label>
                                <select class="form-select" id="frequencia" name="frequency">
                                    <option value="1 vez por dia">1 vez por dia</option>
                                    <option value="2 vezes por dia">2 vezes por dia</option>
                                    <option value="3 vezes por dia">3 vezes por dia</option>
                                    <option value="De hora em hora">De hora em hora</option>
                                </select>
                            </div>
                            <div class="mb-3" id="horarios-box">
                                <label class="form-label">Horário(s)</label>
                                <div id="horarios-inputs" class="d-flex gap-2"></div>
                            </div>
                            <div class="mb-3 d-none" id="periodo-box">
                                <label class="form-label">Período (De hora em hora)</label>
                                <div class="d-flex gap-2">
                                    <input type="time" class="form-control" id="period_start" name="period_start" value="08:00">
                                    <input type="time" class="form-control" id="period_end" name="period_end" value="20:00">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Modo</label>
                                <select class="form-select" id="modo" name="mode">
                                    <option value="daily">Posts Diários</option>
                                    <option value="specific_days">Dias Específicos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Calendário para Dias Específicos -->
                    <div class="d-none" id="calendario-box">
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnMesAnterior">
                                                <i class="bi bi-chevron-left"></i>
                                            </button>
                                            <h6 class="mb-0" id="mesAtual">Janeiro 2024</h6>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnMesProximo">
                                                <i class="bi bi-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="row text-center mb-2">
                                            <div class="col">Dom</div>
                                            <div class="col">Seg</div>
                                            <div class="col">Ter</div>
                                            <div class="col">Qua</div>
                                            <div class="col">Qui</div>
                                            <div class="col">Sex</div>
                                            <div class="col">Sáb</div>
                                        </div>
                                        <div id="calendario-dias" class="row g-1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Dias Selecionados</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="dias-selecionados-lista" class="mb-3">
                                            <p class="text-muted small">Nenhum dia selecionado</p>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="btnLimparDias">
                                            Limpar Seleção
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btnSubmit">
                        <span class="spinner-border spinner-border-sm d-none" id="btn-spinner" role="status"></span>
                        <span id="btn-text">Agendar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function() {
    // Configurar token CSRF para todas as requisições AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Variáveis globais para o calendário
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let selectedDays = [];
    
    // Helpers
    function showAlert(msg, type = 'success', area = '#alert-area') {
        $(area).html(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">${msg}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button></div>`);
    }
    function clearAlert(area = '#alert-area') { $(area).html(''); }

    // Funções do calendário
    function getDaysInMonth(month, year) {
        return new Date(year, month + 1, 0).getDate();
    }
    
    function getFirstDayOfMonth(month, year) {
        return new Date(year, month, 1).getDay();
    }
    
    function formatDate(day, month, year) {
        return `${String(day).padStart(2, '0')}/${String(month + 1).padStart(2, '0')}/${year}`;
    }
    
    function updateCalendar() {
        const daysInMonth = getDaysInMonth(currentMonth, currentYear);
        const firstDay = getFirstDayOfMonth(currentMonth, currentYear);
        const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
                           'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        
        $('#mesAtual').text(`${monthNames[currentMonth]} ${currentYear}`);
        
        let html = '';
        
        // Dias vazios no início
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="col"></div>';
        }
        
        // Dias do mês
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = formatDate(day, currentMonth, currentYear);
            const isSelected = selectedDays.includes(dateStr);
            const isToday = day === currentDate.getDate() && currentMonth === currentDate.getMonth() && currentYear === currentDate.getFullYear();
            
            let classes = 'col text-center p-1 border rounded cursor-pointer';
            if (isSelected) {
                classes += ' bg-success text-white';
            } else if (isToday) {
                classes += ' bg-primary text-white';
            } else {
                classes += ' bg-light';
            }
            
            html += `<div class="${classes}" data-day="${day}" data-date="${dateStr}">${day}</div>`;
        }
        
        $('#calendario-dias').html(html);
        updateSelectedDaysList();
    }
    
    function updateSelectedDaysList() {
        const $lista = $('#dias-selecionados-lista');
        if (selectedDays.length === 0) {
            $lista.html('<p class="text-muted small">Nenhum dia selecionado</p>');
        } else {
            let html = '';
            selectedDays.forEach(day => {
                html += `<div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small">${day}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-day" data-date="${day}">
                        <i class="bi bi-x"></i>
                    </button>
                </div>`;
            });
            $lista.html(html);
        }
    }
    
    // Eventos do calendário
    $(document).on('click', '#calendario-dias .col[data-day]', function() {
        const dateStr = $(this).data('date');
        const index = selectedDays.indexOf(dateStr);
        
        if (index === -1) {
            selectedDays.push(dateStr);
            $(this).removeClass('bg-light bg-primary').addClass('bg-success text-white');
        } else {
            selectedDays.splice(index, 1);
            $(this).removeClass('bg-success text-white').addClass('bg-light');
        }
        
        updateSelectedDaysList();
    });
    
    $(document).on('click', '.btn-remove-day', function() {
        const dateStr = $(this).data('date');
        selectedDays = selectedDays.filter(day => day !== dateStr);
        updateCalendar();
    });
    
    $('#btnMesAnterior').on('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        updateCalendar();
    });
    
    $('#btnMesProximo').on('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateCalendar();
    });
    
    $('#btnLimparDias').on('click', function() {
        selectedDays = [];
        updateCalendar();
    });

    // Carregar agendamentos
    function carregarAgendamentos() {
        $.getJSON('/schedules', function(data) {
            const $tbody = $('#tabela-agendamentos tbody').empty();
            if (data.length === 0) {
                $('#loading-agendamentos').removeClass('d-none').text('Nenhum agendamento cadastrado.');
                $('#tabela-agendamentos').addClass('d-none');
                return;
            }
            $('#loading-agendamentos').addClass('d-none');
            $('#tabela-agendamentos').removeClass('d-none');
            data.forEach(function(ag) {
                let horarios = (ag.hours && ag.hours.length) ? ag.hours.map(h => h.hour).join(', ') : '-';
                let periodo = ag.periodo && ag.periodo.length ? ag.periodo.map(p => p.periodo_inicio + ' - ' + p.periodo_fim).join(', ') : '-';
                let grupos = (ag.external_groups && ag.external_groups.length) ? ag.external_groups.map(g => g.group_id).join(', ') : '-';
                let dias = (ag.days && ag.days.length) ? ag.days.map(d => moment(d.date).format('DD/MM/YYYY')).join(', ') : '-';
                let statusBadge = ag.status === 'active' ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>';
                
                // Botão de status com ícone dinâmico
                let statusBtn = ag.status === 'active' ? 
                    `<button class="btn btn-sm btn-warning toggle-status-btn" data-id="${ag.id}" data-status="active" title="Pausar">
                        <i class="bi bi-pause-circle-fill"></i>
                    </button>` :
                    `<button class="btn btn-sm btn-success toggle-status-btn" data-id="${ag.id}" data-status="inactive" title="Ativar">
                        <i class="bi bi-play-circle-fill"></i>
                    </button>`;
                
                $tbody.append(`<tr>
                    <td>${ag.description || '-'}</td>
                    <td>${ag.frequency}</td>
                    <td>${ag.mode === 'specific_days' ? 'Dias Específicos' : 'Diário'}</td>
                    <td>${ag.frequency === 'De hora em hora' ? periodo : horarios}</td>
                    <td>${grupos}</td>
                    <td>${dias}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-primary" onclick="editarAgendamento(${ag.id})" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            ${statusBtn}
                            <button class="btn btn-danger" onclick="excluirAgendamento(${ag.id})" title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>`);
            });
        }).fail(function(xhr, status, error) {
            console.error('Erro ao carregar agendamentos:', error);
            $('#loading-agendamentos').removeClass('d-none').text('Erro ao carregar agendamentos.');
        });
    }

    // Carregar grupos com indicador de carregamento
    function carregarGrupos() {
        $('#loading-grupos').removeClass('d-none');
        $('#grupos-lista').html('<div class="text-muted small">Carregando grupos...</div>');
        
        $.getJSON('/groups', function(resp) {
            $('#loading-grupos').addClass('d-none');
            
            if (resp.success && resp.groups) {
                let html = '';
                resp.groups.forEach(function(g) {
                    html += `<span class="badge bg-info text-dark grupo-badge" data-id="${g.id}" style="cursor:pointer;">${g.name}</span> `;
                });
                $('#grupos-lista').html(html);
                
                // Mostrar mensagem se estiver usando grupos de exemplo
                if (resp.message) {
                    showAlert(resp.message, 'info', '#alert-modal-area');
                }
            } else {
                $('#grupos-lista').html('<span class="text-danger">Nenhum grupo encontrado.</span>');
            }
        }).fail(function(xhr, status, error) {
            $('#loading-grupos').addClass('d-none');
            console.error('Erro ao carregar grupos:', error);
            $('#grupos-lista').html('<span class="text-danger">Erro ao carregar grupos. Verifique se há uma instância ativa.</span>');
        });
    }

    // Modal: abrir para novo
    $('#btnNovoAgendamento').on('click', function() {
        $('#formAgendamento')[0].reset();
        $('#agendamento_id').val('');
        $('#modalAgendamentoLabel').html('<i class="bi bi-calendar-plus"></i> Novo Agendamento');
        $('#btnSubmit').text('Agendar');
        $('#calendario-box').addClass('d-none');
        $('#periodo-box').addClass('d-none');
        $('#horarios-box').removeClass('d-none');
        $('#modalAgendamento').modal('show');
        carregarGrupos();
        clearAlert('#alert-modal-area');
        atualizarHorariosInputs();
        gruposSelecionados = [];
        selectedDays = [];
        atualizarGruposSelecionados();
        updateCalendar();
    });

    // Modal: modo dias específicos
    $('#modo').on('change', function() {
        if ($(this).val() === 'specific_days') {
            $('#calendario-box').removeClass('d-none');
        } else {
            $('#calendario-box').addClass('d-none');
        }
    });

    // Modal: frequência
    $('#frequencia').on('change', function() {
        if ($(this).val() === 'De hora em hora') {
            $('#periodo-box').removeClass('d-none');
            $('#horarios-box').addClass('d-none');
        } else {
            $('#periodo-box').addClass('d-none');
            $('#horarios-box').removeClass('d-none');
        }
        atualizarHorariosInputs();
    });

    // Horários dinâmicos
    function atualizarHorariosInputs() {
        let freq = $('#frequencia').val();
        let n = 1;
        if (freq === '2 vezes por dia') n = 2;
        if (freq === '3 vezes por dia') n = 3;
        let html = '';
        for (let i = 0; i < n; i++) {
            html += `<input type="time" class="form-control" name="hours[]" value="${['08:00','12:00','16:00'][i] || ''}" style="max-width:120px;">`;
        }
        $('#horarios-inputs').html(html);
    }

    // Seleção de grupos
    let gruposSelecionados = [];
    $(document).on('click', '.grupo-badge', function() {
        let id = $(this).data('id');
        if ($(this).hasClass('bg-info')) {
            $(this).removeClass('bg-info').addClass('bg-success text-white');
            gruposSelecionados.push(id);
        } else {
            $(this).removeClass('bg-success text-white').addClass('bg-info text-dark');
            gruposSelecionados = gruposSelecionados.filter(g => g !== id);
        }
        atualizarGruposSelecionados();
    });
    function atualizarGruposSelecionados() {
        $('#group_ids').val(gruposSelecionados.join(','));
    }

    // Submissão do formulário com indicador de carregamento
    $('#formAgendamento').on('submit', function(e) {
        e.preventDefault();
        clearAlert('#alert-modal-area');
        
        // Validações básicas
        if (gruposSelecionados.length === 0) {
            showAlert('Selecione pelo menos um grupo.', 'danger', '#alert-modal-area');
            return;
        }
        
        if ($('#modo').val() === 'specific_days' && selectedDays.length === 0) {
            showAlert('Selecione pelo menos um dia específico.', 'danger', '#alert-modal-area');
            return;
        }
        
        // Mostrar indicador de carregamento no botão
        $('#btn-spinner').removeClass('d-none');
        $('#btn-text').text($('#agendamento_id').val() ? 'Atualizando...' : 'Agendando...');
        $('#btnSubmit').prop('disabled', true);
        
        let formData = new FormData(this);
        formData.set('group_ids', gruposSelecionados.join(','));
        
        // Dias específicos do calendário
        if ($('#modo').val() === 'specific_days') {
            formData.set('days', selectedDays.join(','));
        }
        
        let url = '/schedules';
        let method = 'POST';
        if ($('#agendamento_id').val()) {
            url = '/schedules/' + $('#agendamento_id').val();
            method = 'PUT';
            formData.append('_method', 'PUT');
        }
        
        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                $('#modalAgendamento').modal('hide');
                showAlert($('#agendamento_id').val() ? 'Agendamento atualizado com sucesso!' : 'Agendamento criado com sucesso!');
                carregarAgendamentos();
            },
            error: function(xhr) {
                let msg = 'Erro ao processar agendamento.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    msg = xhr.responseJSON.error;
                } else if (xhr.status === 419) {
                    msg = 'Erro de autenticação. Recarregue a página e tente novamente.';
                }
                showAlert(msg, 'danger', '#alert-modal-area');
            },
            complete: function() {
                // Restaurar botão
                $('#btn-spinner').addClass('d-none');
                $('#btn-text').text($('#agendamento_id').val() ? 'Atualizar' : 'Agendar');
                $('#btnSubmit').prop('disabled', false);
            }
        });
    });

    // Evento para botões de toggle status
    $(document).on('click', '.toggle-status-btn', function() {
        const $btn = $(this);
        const id = $btn.data('id');
        const currentStatus = $btn.data('status');
        
        // Desabilitar botão temporariamente
        $btn.prop('disabled', true);
        
        $.ajax({
            url: '/schedules/' + id + '/toggle-status',
            method: 'PATCH',
            success: function(resp) {
                showAlert('Status do agendamento alterado com sucesso!');
                carregarAgendamentos();
            },
            error: function() {
                showAlert('Erro ao alterar status do agendamento.', 'danger');
                $btn.prop('disabled', false);
            }
        });
    });

    // Inicial
    carregarAgendamentos();
});

// Funções globais para ações
function editarAgendamento(id) {
    // Mostrar indicador de carregamento
    $('#modalAgendamentoLabel').html('<i class="bi bi-pencil"></i> Carregando...');
    $('#modalAgendamento').modal('show');
    
    $.getJSON('/schedules/' + id, function(ag) {
        $('#agendamento_id').val(ag.id);
        $('#descricao').val(ag.description);
        $('#frequencia').val(ag.frequency);
        $('#modo').val(ag.mode);
        $('#modalAgendamentoLabel').html('<i class="bi bi-pencil"></i> Editar Agendamento');
        $('#btnSubmit').text('Atualizar');
        
        // Configurar horários
        if (ag.frequency === 'De hora em hora') {
            $('#periodo-box').removeClass('d-none');
            $('#horarios-box').addClass('d-none');
            if (ag.periodo && ag.periodo.length > 0) {
                $('#period_start').val(ag.periodo[0].periodo_inicio);
                $('#period_end').val(ag.periodo[0].periodo_fim);
            }
        } else {
            $('#periodo-box').addClass('d-none');
            $('#horarios-box').removeClass('d-none');
            atualizarHorariosInputs();
            if (ag.hours && ag.hours.length > 0) {
                ag.hours.forEach((h, i) => {
                    $(`input[name="hours[]"]`).eq(i).val(h.hour);
                });
            }
        }
        
        // Configurar dias específicos
        if (ag.mode === 'specific_days') {
            $('#calendario-box').removeClass('d-none');
            if (ag.days && ag.days.length > 0) {
                selectedDays = ag.days.map(d => moment(d.date).format('DD/MM/YYYY'));
                updateCalendar();
            }
        } else {
            $('#calendario-box').addClass('d-none');
        }
        
        // Configurar grupos
        gruposSelecionados = ag.external_groups ? ag.external_groups.map(g => g.group_id) : [];
        carregarGrupos();
        setTimeout(() => {
            gruposSelecionados.forEach(id => {
                $(`.grupo-badge[data-id="${id}"]`).removeClass('bg-info').addClass('bg-success text-white');
            });
            atualizarGruposSelecionados();
        }, 500);
        
        clearAlert('#alert-modal-area');
    }).fail(function() {
        $('#modalAgendamento').modal('hide');
        showAlert('Erro ao carregar dados do agendamento.', 'danger');
    });
}

function excluirAgendamento(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir este agendamento? Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar indicador de carregamento
            Swal.fire({
                title: 'Excluindo...',
                text: 'Aguarde enquanto excluímos o agendamento.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: '/schedules/' + id,
                method: 'DELETE',
                success: function() {
                    Swal.fire(
                        'Excluído!',
                        'O agendamento foi excluído com sucesso.',
                        'success'
                    );
                    carregarAgendamentos();
                },
                error: function() {
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o agendamento.',
                        'error'
                    );
                }
            });
        }
    });
}

function toggleStatus(id) {
    // Esta função agora é tratada pelo evento click no documento
    // Mantida para compatibilidade
}
</script>
@endpush 
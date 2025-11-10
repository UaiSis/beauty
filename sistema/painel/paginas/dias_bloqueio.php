<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'dias_bloqueio';

//verificar se ele tem a permissão de estar nessa página
if(@$dias_bloqueio == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

?>

<style>
	/* Página de Dias de Bloqueio Moderna */
	.bloqueio-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.bloqueio-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.bloqueio-header-content {
		flex: 1;
	}

	.bloqueio-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.bloqueio-title-icon {
		width: 40px;
		height: 40px;
		background: rgba(0, 122, 99, 0.12);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #007A63;
		font-size: 20px;
		flex-shrink: 0;
	}

	.bloqueio-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.bloqueio-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.bloqueio-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	/* Formulário de Adicionar */
	.add-bloqueio-card {
		background: #fff;
		border-radius: 16px;
		padding: 24px;
		margin-bottom: 24px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.add-bloqueio-card label {
		font-weight: 600;
		font-size: 13px;
		color: #495057;
		margin-bottom: 8px;
		text-transform: uppercase;
		letter-spacing: 0.3px;
	}

	.add-bloqueio-card .form-control {
		border: 2px solid #e9ecef;
		border-radius: 10px;
		padding: 10px 16px;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.add-bloqueio-card .form-control:focus {
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	.add-bloqueio-card .btn-primary {
		background: #007A63;
		border: none;
		border-radius: 10px;
		padding: 10px 24px;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s ease;
		box-shadow: 0 4px 12px rgba(0, 122, 99, 0.2);
	}

	.add-bloqueio-card .btn-primary:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
	}

	/* Cards de Bloqueio (estilo planos) */
	.bloqueio-card {
		background: #fff;
		border-radius: 12px;
		padding: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		border-left: 4px solid #ef5350;
		position: relative;
		transition: all 0.3s ease;
	}

	.bloqueio-card:hover {
		transform: translateY(-4px);
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
	}

	.bloqueio-card-status {
		position: absolute;
		top: 16px;
		right: 16px;
		background: rgba(239, 83, 80, 0.15);
		color: #ef5350;
		padding: 4px 12px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: 0.5px;
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.bloqueio-card-date-big {
		font-size: 28px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 16px;
		margin-top: 8px;
	}

	.bloqueio-card-info {
		display: flex;
		flex-direction: column;
		gap: 8px;
		margin-bottom: 16px;
		padding-bottom: 16px;
		border-bottom: 1px solid #f0f0f0;
	}

	.bloqueio-card-info-item {
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 13px;
		color: #6c757d;
	}

	.bloqueio-card-info-item i {
		color: #007A63;
		font-size: 14px;
		width: 16px;
	}

	.bloqueio-card-info-item strong {
		color: #495057;
	}

	.bloqueio-card-actions {
		display: flex;
		gap: 8px;
		justify-content: center;
	}

	.bloqueio-action-btn {
		padding: 8px 16px;
		border-radius: 8px;
		font-size: 13px;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 6px;
		transition: all 0.2s ease;
		border: none;
		cursor: pointer;
	}

	.bloqueio-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.bloqueio-action-btn.delete:hover {
		background: #ef5350;
		color: #fff;
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(239, 83, 80, 0.3);
	}

	/* Tabela Moderna */
	.table-card-modern {
		background: #fff;
		border-radius: 16px;
		padding: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}


	/* Empty State */
	.empty-state {
		text-align: center;
		padding: 60px 20px;
		background: #fff;
		border-radius: 16px;
	}

	.empty-state i {
		font-size: 48px;
		color: #dee2e6;
		margin-bottom: 16px;
	}

	.empty-state p {
		font-size: 16px;
		color: #6c757d;
		font-weight: 500;
		margin: 0;
	}

	.empty-state small {
		font-size: 13px;
		color: #adb5bd;
		margin-top: 8px;
		display: block;
	}

	/* Responsivo */
	@media (max-width: 768px) {
		.bloqueio-page-modern {
			padding: 16px;
		}

		.bloqueio-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.bloqueio-title-wrapper {
			gap: 10px;
		}

		.bloqueio-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.bloqueio-title {
			font-size: 20px;
		}

		.bloqueio-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.bloqueio-divider {
			margin-left: 46px;
			width: 100px;
		}

	}
</style>

<div class="bloqueio-page-modern">
	
	<div class="bloqueio-header">
		<div class="bloqueio-header-content">
			<div class="bloqueio-title-wrapper">
				<div class="bloqueio-title-icon">
					<i class="fa fa-ban"></i>
				</div>
				<h1 class="bloqueio-title">Dias de Bloqueio</h1>
			</div>
			<p class="bloqueio-subtitle">Bloqueie datas específicas para não aceitar agendamentos</p>
			<div class="bloqueio-divider"></div>
		</div>
	</div>

	<div class="add-bloqueio-card">
		<div style="display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap;">
			<div style="flex: 1; min-width: 250px;">
				<label style="
					font-weight: 600;
					font-size: 13px;
					color: #495057;
					margin-bottom: 8px;
					text-transform: uppercase;
					letter-spacing: 0.3px;
					display: block;
				">Data para Bloquear</label>
				<input type="date" name="data" id="data" form="form-dias" class="form-control" required style="
					border: 2px solid #e9ecef;
					border-radius: 10px;
					padding: 10px 16px;
					font-size: 14px;
				">
			</div>

			<div>
				<button type="submit" form="form-dias" class="btn btn-primary" style="
					background: #007A63;
					border: none;
					border-radius: 10px;
					padding: 10px 24px;
					font-weight: 600;
					font-size: 14px;
					box-shadow: 0 4px 12px rgba(0, 122, 99, 0.2);
					transition: all 0.3s ease;
					white-space: nowrap;
				">
					<i class="fa fa-ban"></i> Bloquear Data
				</button>
			</div>
		</div>

		<form id="form-dias">
			<input type="hidden" name="id" id="id_dias" value="<?php echo $id_usuario ?>">
		</form>

		<div id="mensagem-dias" style="margin-top: 12px;"></div>
	</div>

	<div class="table-card-modern">
		<div id="listar-dias"></div>
	</div>
	
</div>



<script type="text/javascript">var pag = "<?=$pag?>"</script>


<script type="text/javascript">
	$(document).ready(function() {
		var func = $("#id_dias").val();
		listarServicos(func)
	});
</script>



<script type="text/javascript">
	

$("#form-dias").submit(function () {

	var func = $("#id_dias").val();
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/inserir-servico.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-dias').text('');
            $('#mensagem-dias').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                //$('#btn-fechar-horarios').click();
                listarServicos(func);          

            } else {

                $('#mensagem-dias').addClass('text-danger')
                $('#mensagem-dias').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});


</script>


<script type="text/javascript">
	function listarServicos(func){
		
    $.ajax({
        url: 'paginas/' + pag + "/listar-servicos.php",
        method: 'POST',
        data: {func},
        dataType: "html",

        success:function(result){
            $("#listar-dias").html(result);
            $('#mensagem-dias-excluir').text('');
        }
    });
}

</script>
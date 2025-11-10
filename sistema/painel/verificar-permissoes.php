<?php 
@session_start();
$id_usuario = $_SESSION['id'];


$home = 'ocultar';
$configuracoes = 'ocultar';
$comanda = 'ocultar';
$marketing = 'ocultar';
$calendario = 'ocultar';
$caixas = 'ocultar';
$planos = 'ocultar';
$verificar_pgtos = 'ocultar';
$dispositivos = 'ocultar';

// Variáveis para menus pai (serão definidas automaticamente)
$menu_vendas = 'ocultar';
$menu_pessoas = 'ocultar';
$menu_cadastros = 'ocultar';
$menu_produtos = 'ocultar';
$menu_financeiro = 'ocultar';
$menu_agendamentos = 'ocultar';
$menu_relatorio = 'ocultar';
$menu_site = 'ocultar';
$menu_ponto = 'ocultar';

//grupo pessoas
$usuarios = 'ocultar';
$funcionarios = 'ocultar';
$clientes = 'ocultar';
$clientes_retorno = 'ocultar';
$fornecedores = 'ocultar';


//grupo cadastros
$servicos = 'ocultar';
$cargos = 'ocultar';
$cat_servicos = 'ocultar';
$grupos = 'ocultar';
$acessos = 'ocultar';
$pgto = 'ocultar';
$dias_bloqueio = 'ocultar';
$assinaturas = 'ocultar';
$frequencias = 'ocultar';

//grupo produtos
$produtos = 'ocultar';
$cat_produtos = 'ocultar';
$estoque = 'ocultar';
$saidas = 'ocultar';
$entradas = 'ocultar';


//grupo financeiro
$vendas = 'ocultar';
$compras = 'ocultar';
$pagar = 'ocultar';
$receber = 'ocultar';
$comissoes = 'ocultar';
$receber_vencidas = 'ocultar';

//agendamentos / servico
$agendamentos = 'ocultar';
$servicos_agenda = 'ocultar';


//relatorios
$rel_produtos = 'ocultar';
$rel_entradas = 'ocultar';
$rel_saidas = 'ocultar';
$rel_comissoes = 'ocultar';
$rel_contas = 'ocultar';
$rel_aniv = 'ocultar';
$rel_lucro = 'ocultar';
$rel_servicos = 'ocultar';
$rel_ina = 'ocultar';

//dados site
$textos_index = 'ocultar';
$comentarios = 'ocultar';

//controle de ponto
$registro_ponto = 'ocultar';
$configuracoes_ponto = 'ocultar';
$meu_ponto = '';

//páginas de funcionário (sempre disponíveis se atendimento = 'Sim')
$agenda = '';
$meus_servicos = '';
$minhas_comissoes = '';
$dias = '';
$servicos_func = '';
$dias_bloqueio_func = '';




$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){}
		$permissao = $res[$i]['permissao'];
		
		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome = $res2[0]['nome'];
		$chave = $res2[0]['chave'];
		$id = $res2[0]['id'];

		if($chave == 'home'){
			$home = '';
		}


		if($chave == 'configuracoes'){
			$configuracoes = '';
		}


		if($chave == 'comanda'){
			$comanda = '';
		}

		if($chave == 'marketing'){
			$marketing = '';
		}

		if($chave == 'calendario'){
			$calendario = '';
		}

		if($chave == 'caixas'){
			$caixas = '';
		}

		if($chave == 'planos'){
			$planos = '';
		}

			if($chave == 'frequencias'){
			$frequencias = '';
		}

			if($chave == 'verificar_pgtos'){
			$verificar_pgtos = '';
		}

			if($chave == 'dispositivos'){
			$dispositivos = '';
		}





		if($chave == 'usuarios'){
			$usuarios = '';
		}

		if($chave == 'funcionarios'){
			$funcionarios = '';
		}

		if($chave == 'clientes'){
			$clientes = '';
		}

		if($chave == 'clientes_retorno'){
			$clientes_retorno = '';
		}

		if($chave == 'fornecedores'){
			$fornecedores = '';
		}





		if($chave == 'servicos'){
			$servicos = '';
		}

		if($chave == 'cargos'){
			$cargos = '';
		}

		if($chave == 'cat_servicos'){
			$cat_servicos = '';
		}

		if($chave == 'grupos'){
			$grupos = '';
		}

		if($chave == 'acessos'){
			$acessos = '';
		}

		if($chave == 'pgto'){
			$pgto = '';
		}

		if($chave == 'dias_bloqueio'){
			$dias_bloqueio = '';
		}

		if($chave == 'assinaturas'){
			$assinaturas = '';
		}





		if($chave == 'produtos'){
			$produtos = '';
		}

		if($chave == 'cat_produtos'){
			$cat_produtos = '';
		}

		if($chave == 'estoque'){
			$estoque = '';
		}

		if($chave == 'saidas'){
			$saidas = '';
		}

		if($chave == 'entradas'){
			$entradas = '';
		}





		if($chave == 'compras'){
			$compras = '';
		}

		if($chave == 'vendas'){
			$vendas = '';
		}

		if($chave == 'pagar'){
			$pagar = '';
		}

		if($chave == 'receber'){
			$receber = '';
		}

		if($chave == 'comissoes'){
			$comissoes = '';
		}

		if($chave == 'receber_vencidas'){
			$receber_vencidas = '';
		}



		if($chave == 'agendamentos'){
			$agendamentos = '';
		}

		if($chave == 'servicos_agenda'){
			$servicos_agenda = '';
		}




		if($chave == 'rel_produtos'){
			$rel_produtos = '';
		}

		if($chave == 'rel_entradas'){
			$rel_entradas = '';
		}

		if($chave == 'rel_saidas'){
			$rel_saidas = '';
		}

		if($chave == 'rel_comissoes'){
			$rel_comissoes = '';
		}

		if($chave == 'rel_contas'){
			$rel_contas = '';
		}

		if($chave == 'rel_aniv'){
			$rel_aniv = '';
		}

		if($chave == 'rel_lucro'){
			$rel_lucro = '';
		}

		if($chave == 'rel_servicos'){
			$rel_servicos = '';
		}

		if($chave == 'rel_ina'){
			$rel_ina = '';
		}





		if($chave == 'textos_index'){
			$textos_index = '';
		}

	if($chave == 'comentarios'){
		$comentarios = '';
	}


	if($chave == 'registro_ponto'){
		$registro_ponto = '';
	}

	if($chave == 'configuracoes_ponto'){
		$configuracoes_ponto = '';
	}


}

}



if($home != 'ocultar'){
	$pag_inicial = 'home';
}else if($atendimento == 'Sim'){
	$pag_inicial = 'agenda';
}else{
	$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' order by id asc limit 1");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){	
			$permissao = $res[0]['permissao'];		
			$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
			$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);		
			$pag_inicial = $res2[0]['chave'];		

	}
}



// Menu Vendas (novo menu)
if($comanda == 'ocultar' and $vendas == 'ocultar' and $planos == 'ocultar'){
	if($menu_vendas != 'active'){
		$menu_vendas = 'ocultar';
	}
}else{
	if($menu_vendas != 'active'){
		$menu_vendas = '';
	}
}

if($usuarios == 'ocultar' and $funcionarios == 'ocultar' and $clientes == 'ocultar' and $clientes_retorno == 'ocultar' and $fornecedores == 'ocultar'){
	if($menu_pessoas != 'active'){
		$menu_pessoas = 'ocultar';
	}
}else{
	if($menu_pessoas != 'active'){
		$menu_pessoas = '';
	}
}



if($servicos == 'ocultar' and $cargos == 'ocultar' and $cat_servicos == 'ocultar' and $grupos == 'ocultar' and $acessos == 'ocultar' and $pgto == 'ocultar' and $dias_bloqueio == 'ocultar' and $assinaturas == 'ocultar' and $frequencias == 'ocultar'){
	if($menu_cadastros != 'active'){
		$menu_cadastros = 'ocultar';
	}
}else{
	if($menu_cadastros != 'active'){
		$menu_cadastros = '';
	}
}



if($produtos == 'ocultar' and $cat_produtos == 'ocultar' and $estoque == 'ocultar' and $saidas == 'ocultar' and $entradas == 'ocultar'){
	if($menu_produtos != 'active'){
		$menu_produtos = 'ocultar';
	}
}else{
	if($menu_produtos != 'active'){
		$menu_produtos = '';
	}
}



if($compras == 'ocultar' and $pagar == 'ocultar' and $receber == 'ocultar' and $comissoes == 'ocultar' and $receber_vencidas == 'ocultar' and $caixas == 'ocultar'){
	if($menu_financeiro != 'active'){
		$menu_financeiro = 'ocultar';
	}
}else{
	if($menu_financeiro != 'active'){
		$menu_financeiro = '';
	}
}



if($agendamentos == 'ocultar' and $servicos_agenda == 'ocultar' and $calendario == 'ocultar'){
	if($menu_agendamentos != 'active'){
		$menu_agendamentos = 'ocultar';
	}
}else{
	if($menu_agendamentos != 'active'){
		$menu_agendamentos = '';
	}
}



if($rel_produtos == 'ocultar' and $rel_lucro == 'ocultar' and $rel_aniv == 'ocultar' and $rel_contas == 'ocultar' and $rel_comissoes == 'ocultar' and $rel_saidas == 'ocultar' and $rel_entradas == 'ocultar' and $rel_servicos == 'ocultar' and $rel_ina == 'ocultar'){
	if($menu_relatorio != 'active'){
		$menu_relatorio = 'ocultar';
	}
}else{
	if($menu_relatorio != 'active'){
		$menu_relatorio = '';
	}
}



if($textos_index == 'ocultar' and $comentarios == 'ocultar' ){
	if($menu_site != 'active'){
		$menu_site = 'ocultar';
	}
}else{
	if($menu_site != 'active'){
		$menu_site = '';
	}
}


if($registro_ponto == 'ocultar' and $configuracoes_ponto == 'ocultar' ){
	if($menu_ponto != 'active'){
		$menu_ponto = 'ocultar';
	}
}else{
	if($menu_ponto != 'active'){
		$menu_ponto = '';
	}
}


 ?>
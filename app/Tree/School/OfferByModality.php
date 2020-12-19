<?php

namespace App\Tree\School;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Session;

class OfferByModality extends Branch {
	
	const YES = 0; 
	const NO = 1; 

	function handle(DecisionTree $tree, Closure $next): DecisionTree {

		$tokens = $tree->getTokens();
		$field = false;	// campo da tabela do banco de dados
		$match = false;
		// varíavel de controle pois algumas modalidades da educação são "compostas" 
		$multipleConditions = true; 
		$message = session('messageSchool');
		
		/* Percorre todos os tokens procurando a ocorrencia de palavras-chave */
		foreach ($tokens as $token => $value) {
			if (preg_match('/fundamental/i', $value))  {
				$message .= " de ensino fundamental";
				/*  Ensino fundamental é composto por anos iniciais e anos finais
				 *	Percorre todos os tokens procurando a ocorrencia dessas palavras-chave 
				 */
				foreach ($tokens as $token => $val) {
					// ao encontrar uma condicao especifica, muda o estado de $multipleConditions
					if (preg_match('/inicial/i', $val)) {
						$field = 'IN_COMUM_FUND_AI';
						$match = true;
						$multipleConditions = false;
						$message .= " anos iniciais";
						break;
					} else if (preg_match('/final/i', $val)) {
						$field = 'IN_COMUM_FUND_AF';	
						$match = true;
						$multipleConditions = false;
						$message .= " anos finais";
						break;
					}
				}
				/* Se $multipleConditions continuar como true, é porque não entrou em nenhuma condição anterior 
				 * É necessário realizar a consulta de forma menos restritiva. Ensino Fundamental é composto por anos Iniciais e Finais
				 * Necessário realizar uma consulta equivalente a: ('IN_COMUM_FUND_AI' = 1 OR 'IN_COMUM_FUND_AF' = 1)
				 */
				if ($multipleConditions){
					$tree->setQuery($tree->getQuery()
						->where(function ($query) {
							$query->where('IN_COMUM_FUND_AI', '=', 1)
								->orWhere('IN_COMUM_FUND_AF', '=', 1);
						})
					);
					$field = 'TODOS FUNDAMENTAL';
				} 
				break;
			} else if (preg_match('/medio/i', $value))  {
				/* Mesma lógica do ensino fundamental
				 * Ensino médio é dividido em: comum, integrado e profissionalizante
				 */
				$message .= " de ensino médio";
				foreach ($tokens as $token => $val) {
					// integrar é o lema de integrado
					if (preg_match('/integrar/i', $val)) {
						$field = 'IN_COMUM_MEDIO_INTEGRADO';
						$match = true;
						$multipleConditions = false;
						$message .= " integrado";
						break;
					// profissional também abrange a palavra profissionalizante
					} else if ( preg_match('/profissional/i', $val)) {
						$field = 'IN_PROFISSIONALIZANTE';
						$match = true;
						$multipleConditions = false;
						$message .= " profissionalizante";
						break;
					} else if (preg_match('/comum/i', $val) || preg_match('/normal/i', $val)) {				
						$field = 'IN_COMUM_MEDIO_MEDIO';
						$match = true;
						$multipleConditions = false;
						$message .= " normal";
						break;
					} 
				}
				if ($multipleConditions){
					$tree->setQuery($tree->getQuery()
						->where(function ($query) {
							$query->where('IN_COMUM_MEDIO_MEDIO', '=', 1)
								->orWhere('IN_COMUM_MEDIO_INTEGRADO', '=', 1)
								->orWhere('IN_PROFISSIONALIZANTE', '=', 1);
						})
					);
				} 
				break;
			} else if (preg_match('/aee/i', $value)) {
				$field = 'TP_AEE';
				$match = true;
				break;
			} else if (preg_match('/creche/i', $value))  {
				$field = 'IN_COMUM_CRECHE';
				$match = true;
				// Quando a pergunta se relacionar à creche, substitui a 'escolas' por 'cheches'
				// pois essa mensagem será apresenta para o usuário
				$message = str_ireplace('Escolas', 'Creches', $message);
				break;		
			} else if (preg_match('/eja/i', $value))  {
				$field = 'IN_EJA';
				$match = true;
				break;
			}
		}

		// O termo pré-escola é procurado na sentença
		if (preg_match('/pr(e|é)( |-)escola/i', $tree->sentence))  {
			$field = 'IN_COMUM_PRE';
			$match = true;
			$message .= " com pré-escola";
		}

		/* Apenas condições específicas (que se referem a um único campo do BD) alteram o status de $match.
		 * Se match for true, adiciona a restrição ao SQL.
		 * Se match for false, não adiciona a restrição pois a consulta já pode ter sido executada com várias condições OU
		 * a pergunta não solicita uma informação processada por esse pipeline. 
 		 */	
		if ($match)
			$tree->setQuery($tree->getQuery()->where($field, '=', 1));	
		
		session(['messageSchool' => $message]);

		return $next($tree);

	}
}

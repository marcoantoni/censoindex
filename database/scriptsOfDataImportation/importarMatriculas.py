import csv
import math
import mysql.connector
from mysql.connector import Error

connection = mysql.connector.connect(
  host="localhost",
  user="debian-sys-maint",
  passwd="HwearQMC4nkPeYmP",
  database="censoindex"
)

line_count = 0
with open('MATRICULA_SUL.CSV') as csv_file:
    csv_reader = csv.reader(csv_file, delimiter='\n')
    sql = ''
    for row in csv_reader:
	
		data = row[0].split("|")
		
		# https://stackoverflow.com/questions/522563/accessing-the-index-in-for-loops
		for idx, val in enumerate(data):
			if val == "":
				data[idx] = 0

		try:
			cursor = connection.cursor()
			sql = "INSERT INTO matriculas (NU_ANO_CENSO, ID_ALUNO, ID_MATRICULA, NU_MES, NU_ANO, NU_IDADE_REFERENCIA, NU_IDADE, TP_SEXO, TP_COR_RACA, TP_NACIONALIDADE, CO_PAIS_ORIGEM, CO_UF_NASC, CO_MUNICIPIO_NASC, CO_UF_END, CO_MUNICIPIO_END, TP_ZONA_RESIDENCIAL, TP_LOCAL_RESID_DIFERENCIADA, IN_NECESSIDADE_ESPECIAL, IN_BAIXA_VISAO, IN_CEGUEIRA, IN_DEF_AUDITIVA, IN_DEF_FISICA, IN_DEF_INTELECTUAL, IN_SURDEZ, IN_SURDOCEGUEIRA, IN_DEF_MULTIPLA, IN_AUTISMO, IN_SUPERDOTACAO, IN_RECURSO_LEDOR, IN_RECURSO_TRANSCRICAO, IN_RECURSO_INTERPRETE, IN_RECURSO_LIBRAS, IN_RECURSO_LABIAL, IN_RECURSO_AMPLIADA_18, IN_RECURSO_AMPLIADA_24, IN_RECURSO_CD_AUDIO, IN_RECURSO_PROVA_PORTUGUES, IN_RECURSO_VIDEO_LIBRAS, IN_RECURSO_BRAILLE, IN_RECURSO_NENHUM, IN_AEE_LIBRAS, IN_AEE_LINGUA_PORTUGUESA, IN_AEE_INFORMATICA_ACESSIVEL, IN_AEE_BRAILLE, IN_AEE_CAA, IN_AEE_SOROBAN, IN_AEE_VIDA_AUTONOMA, IN_AEE_OPTICOS_NAO_OPTICOS, IN_AEE_ENRIQ_CURRICULAR, IN_AEE_DESEN_COGNITIVO, IN_AEE_MOBILIDADE, TP_OUTRO_LOCAL_AULA, IN_TRANSPORTE_PUBLICO, TP_RESPONSAVEL_TRANSPORTE, IN_TRANSP_BICICLETA, IN_TRANSP_MICRO_ONIBUS, IN_TRANSP_ONIBUS, IN_TRANSP_TR_ANIMAL, IN_TRANSP_VANS_KOMBI, IN_TRANSP_OUTRO_VEICULO, IN_TRANSP_EMBAR_ATE5, IN_TRANSP_EMBAR_5A15, IN_TRANSP_EMBAR_15A35, IN_TRANSP_EMBAR_35, TP_ETAPA_ENSINO, IN_ESPECIAL_EXCLUSIVA, IN_REGULAR, IN_EJA, IN_PROFISSIONALIZANTE, ID_TURMA, CO_CURSO_EDUC_PROFISSIONAL, TP_MEDIACAO_DIDATICO_PEDAGO, NU_DURACAO_TURMA, NU_DUR_ATIV_COMP_MESMA_REDE, NU_DUR_ATIV_COMP_OUTRAS_REDES, NU_DUR_AEE_MESMA_REDE, NU_DUR_AEE_OUTRAS_REDES, NU_DIAS_ATIVIDADE, TP_UNIFICADA, TP_TIPO_ATENDIMENTO_TURMA, TP_TIPO_LOCAL_TURMA, CO_ENTIDADE, CO_REGIAO, CO_MESORREGIAO, CO_MICRORREGIAO, CO_UF, CO_MUNICIPIO, CO_DISTRITO, TP_DEPENDENCIA, TP_LOCALIZACAO, TP_CATEGORIA_ESCOLA_PRIVADA, IN_CONVENIADA_PP, TP_CONVENIO_PODER_PUBLICO, IN_MANT_ESCOLA_PRIVADA_EMP, IN_MANT_ESCOLA_PRIVADA_ONG, IN_MANT_ESCOLA_PRIVADA_OSCIP, IN_MANT_ESCOLA_PRIV_ONG_OSCIP, IN_MANT_ESCOLA_PRIVADA_SIND, IN_MANT_ESCOLA_PRIVADA_SIST_S, IN_MANT_ESCOLA_PRIVADA_S_FINS, TP_REGULAMENTACAO, TP_LOCALIZACAO_DIFERENCIADA, IN_EDUCACAO_INDIGENA) VALUES (%s,'%s',%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)" % (data[0],data[1],data[2],data[3],data[4],data[5],data[6],data[7],data[8],data[9],data[10],data[11],data[12],data[13],data[14],data[15],data[16],data[17],data[18],data[19],data[20],data[21],data[22],data[23],data[24],data[25],data[26],data[27],data[28],data[29],data[30],data[31],data[32],data[33],data[34],data[35],data[36],data[37],data[38],data[39],data[40],data[41],data[42],data[43],data[44],data[45],data[46],data[47],data[48],data[49],data[50],data[51],data[52],data[53],data[54],data[55],data[56],data[57],data[58],data[59],data[60],data[61],data[62],data[63],data[64],data[65],data[66],data[67],data[68],data[69],data[70],data[71],data[72],data[73],data[74],data[75],data[76],data[77],data[78],data[79],data[80],data[81],data[82],data[83],data[84],data[85],data[86],data[87],data[88],data[89],data[90],data[91],data[92],data[93],data[94],data[95],data[96],data[97],data[98],data[99],data[100],data[101],data[102])	
			cursor.execute(sql)
			connection.commit()	
		except TypeError as e:
			print(e)
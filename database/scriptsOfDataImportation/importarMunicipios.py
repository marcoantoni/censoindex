import csv
import math
import mysql.connector
from mysql.connector import Error

connection = mysql.connector.connect(
  host="localhost",
  user="USER",
  passwd="PASS",
  database="censoindex"
)

line_count = 0
with open('municipios.csv') as csv_file:
	csv_reader = csv.reader(csv_file, delimiter=',')

	for row in csv_reader:
		try:
			cursor = connection.cursor()
			sql = "INSERT INTO municipios(id, CO_MUNICIPIO, NOME, UF, CO_UF) VALUES (%s, %s,'%s','%s', %s)" % (row[0], row[0], row[1], row[2], row[3])
			cursor.execute(sql)
			connection.commit()	
		except TypeError as e:
			print("Erro" )
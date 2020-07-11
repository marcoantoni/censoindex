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
with open('estados.csv') as csv_file:
	csv_reader = csv.reader(csv_file, delimiter=',')

	for row in csv_reader:
		try:
			cursor = connection.cursor()
			sql = "INSERT INTO uf(co_uf, no_estado, no_uf) VALUES (%s,'%s','%s')" % (row[0], row[1] ,row[2])
			cursor.execute(sql)
			connection.commit()	
		except TypeError as e:
			print("Erro" )
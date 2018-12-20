import MySQLdb
import sys
import pexpect
from pexpect import pxssh
import getpass
import paramiko
from contextlib import contextmanager
import os
host = 'gsr-india02-lnx'
username = 'gpandi'
password = 'Zxcasdqwe*123'
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
print "creating connection"
ssh.connect(host, username=username, password=password)
print "connected"
ssh.close()	   

jobs=sys.argv[1]
db = MySQLdb.connect(host="sjc-dbdl-mysql4",    # your host, usually localhost
                     user="irs",         # your username
                     passwd="irs",  # your password
                     db="irs")        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()
job=jobs.split(",")
flex_job=""
lag_job=""
vpws_job=""
job_q={};

#print job 
for script in job: 
	# Use all the SQL you like
	
	print type(script)
	sql=cur.execute("select * from job_suit where job='%s'"%script)
# print all the first cell of all the rows
	for row in cur.fetchall():
		if(row[0] =="Flex" and row[4] == "python" and row[5] == "Gamma"):
			if (flex_job==""):
				flex_job+="python -m ats.easypy %s -tf TOPOLOGY.scapa_5rtr_tgn"%script
		
			else:
				flex_job+=';'+"python -m ats.easypy %s -tf TOPOLOGY.scapa_5rtr_tgn"%script
	
		elif(row[0] =="vpws" and row[4] == "python" and row[5] == "Gamma"):
			if (vpws_job==""):
				vpws_job+="python -m ats.easypy %s -tf TOPOLOGY.scapa_5rtr_tgn"%script
		
			else:
				vpws_job+=';'+"python -m ats.easypy %s -tf TOPOLOGY.scapa_5rtr_tgn"%script
		elif(row[0] =="lag" and row[4] == "python" and row[5] == "Gamma"):
			if (lag_job==""):
				lag_job+="python -m ats.easypy %s -tf TOPOLOGY.scapa_5rtr_tgn"%script
		
			else:
				lag_job+=';'+"python -m ats.easypy %s -tf TOPOLOGY.scapa_5rtr_tgn"%script
				
job_q['flex']=flex_job
job_q['vpws']=vpws_job
job_q['lag']=lag_job
			
for keys,values in job_q.items():
	if (values == "flex_job"):
		path="/ws/gpandi-bgl/Scapa/flex_lsp_May2017/june15";
		env="activate_irfan.csh";

	elif(values =="vpws_job"):

		path="/ws/gpandi-bgl/Scapa/VPWS";
		env="activate_CFM.csh";

	else:	
		path="/ws/gpandi-bgl/Scapa/LAG";
		env="/ws/gpandi-bgl/Scapa/VPWS/activate_CFM.csh";
	

	print values



db.close()
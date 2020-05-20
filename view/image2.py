#!/usr/bin/env python2.7
import mysql.connector
from mysql.connector import Error
import sys
import os, shutil

ff = sys.argv[1]

def write_file(data, filename):
    with open(filename, 'wb') as file:
        a = file.write(data)
    return a

try:
    connection = mysql.connector.connect(host='localhost',
                                         database='SENTINELX',
                                         user='root',
                                         password='',
                                         buffered=True,
                                         )

    cursor1 = connection.cursor()
    cursor1.execute("SELECT f_key FROM dl_event_tab WHERE seq_no=%s" %ff)
    value =cursor1.fetchone()
    name = value[0]
    names = name.split('_')
    c = names[0]
    ti = names[1]

    cursor = connection.cursor()
    sql_select_query = "SELECT * FROM image_tab where unixtime>=%s and unixtime<=%s and channel_id=%s" 
    ta = int(ti)-60
    tb = int(ti)+60
    d = int(ta)
    e = int(tb)
    t = (d, e, c, )
    cursor.execute(sql_select_query, t)
    record = cursor.fetchall()

    count = 0
    folder = "/home/asus/Desktop/POKA/a/"
    for filename in os.listdir(folder):
        file_path = os.path.join(folder, filename)
        try:
            if os.path.isfile(file_path) or os.path.islink(file_path):
                os.unlink(file_path)
            elif os.path.isdir(file_path):
                shutil.rmtree(file_path)
        except Exception as e:
            print('Failed to delete %s. Reason: %s' % (file_path, e))   

    for r in record:
        Seqno = r[0]
        channel_id = r[1]
        Image = r[2]
        unixtime = r[3]
        
        photo = "/home/asus/Desktop/POKA/a/" + str(count) + ".jpg"
        write_file(Image,photo)
        count += 1

except Error as e:
    print("Error while connecting to MySQL", e)
finally:
    connection.close()

os.system("python3 /home/asus/Desktop/POKA/video.py")

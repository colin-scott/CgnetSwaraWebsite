import os
import sys
import time
import logging
from watchdog.observers import Observer
from watchdog.events import LoggingEventHandler
from multiprocessing import Process
import wave
from pydub import AudioSegment
from chk_blank import get_res
import MySQLdb;

count = 0

class Event(LoggingEventHandler):
    def dispatch(self, event):
	#print "started execution ... "
	if(event.event_type=='created' or event.event_type=='moved'):
		temp2 = str(event.src_path);
		res = temp2.split("/");
		len_res = len(res)
		file1 = res[int(len_res)-1]
		if(".mp3" in file1):
			ret = get_res(file1)
			print "ret is : ",ret
			if(int(ret) == 0):				
				db = MySQLdb.connect("127.0.0.1","root","Wmtp00lr!","audiwikiswara");
				cursor = db.cursor();
				qry = "update lb_postings set status=7 where audio_file = '"+file1+"';";
				cursor.execute(qry);
				db.close();
				#print "returned 0"
				#print  "qry : ",qry
			else:
				pass
				#print "returned 1"
			#result = file1+" : "+str(ret)
			#f = open("info.txt","w")
			#f.write(result)
	
def launch():
    logging.basicConfig(level=logging.INFO,
                        format='%(asctime)s - %(message)s',
                        datefmt='%Y-%m-%d %H:%M:%S')
    path = '/var/lib/asterisk/sounds/audiowikiIndia/web/'
    event_handler = Event()
    observer = Observer()
    observer.schedule(event_handler, path, recursive=True)
    observer.start()
    try:
        while True:
            time.sleep(10)
    except KeyboardInterrupt:
        observer.stop()
    observer.join()


if __name__ == "__main__":
    print "The main process started \n"
    pid = os.fork()
    if pid > 0:
	print "The pid is : ",pid
        sys.exit(0)
    else :
	print "Direct launch ... "
	launch();
    print "The main process ended \n"


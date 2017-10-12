import sys
import wave
from pydub import AudioSegment
import os


def get_res(file1):
	loc1 = "/var/lib/asterisk/sounds/audiowikiIndia/web/"+file1;
	loc2 = "wave/"+file1.split(".")[0]+".wav";
	sound = AudioSegment.from_mp3(loc1);
	sound.export(loc2,format="wav");
	c1 = 0
	c2 = 0
	duration = 0
	ip = wave.open(loc2,'r');
	frames = ip.getnframes()
	rate = ip.getframerate()
	duration = frames / float(rate)

	for i in range(ip.getnframes()):
		iframes = ip.readframes(1)
		amp = int(iframes.encode('hex'),16)
		if(amp<3000):
			c1 = c1+1
		else:
			c2 = c2+1



	tot = c1+c2;
	res1 = float(c1)/float(tot)*100;
	res = res1/duration;
	set1 = -1
	print "res is : ",res
	if res<0.27:
		set1 = 1
	else:
		set1 = 0
	return set1



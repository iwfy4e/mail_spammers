import smtplib, urllib
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
import xml.etree.ElementTree as ET

arr = [
		# 'sales@myntra.com',
		# 'noreply@nicebir.com',
		# 'info@wisdomjobs.net',
		# 'rbi.transferdepartment110@gmail.com',
		# 'reply@hello.payscale.com',
		# 'aghi.m@mashape.com',
		# 'feedback@goibibopromotions.com',
		# 'yourcreditcard@hdfcbank.net',
		# 'business.news@biorhythmfree.com',
		'chatinvite@lovenmarry.com',
		'emma.hubbard@wandisco.com',
		'newsletter@mailers.e-redbus.in',
		'raffaelladicerbo@gmail.com',
		'fauvel@conniecoe.com',
		'ddddd@alfa.com',
		'questforbliss.chdiz@gmail.com',
		'alerts@mailing.quikr.com',
		'noreply@jobshunk.in',
		'holidays@easemytrip.com',
		'newsletter@machinehappy.com',
		'noreply@indiafirstlife.com',
		'hrindia@cr2.in',
		'perfectplacerdel@gmail.com',
		'subhendu@venturesity.com',
		'recruiteritech@i-techsoftware.com',
		'gtscampus@in.ibm.com',
		'hr@msp-group.co.uk',
		'srishti.chaube@naukri.com',
		'geetha.subramani@adecco.co.in',
		'riddhi.kansara@radixweb.com',
		'noreply@zaamlly.com',
		'sanjay@thinkpeople.in',
		'khushali.shah@radixweb.com',
		'neatensgroup@gmail.com',
		'resumes@radixweb.com',
		'pravin.cp2@wipro.com',
		'info@rbc.ca'
	]

#debugging

# arr = ['test@gmail.com']

mailserver = smtplib.SMTP('smtp.gmail.com',587)
mailserver.ehlo()
mailserver.starttls()
mailserver.ehlo()
fp = urllib.urlopen('https://www.yahoo.com/health/rss')
xml_content = fp.read()
root = ET.fromstring(xml_content)
mailserver.login('iwillmailyou4ever@gmail.com', 'IWONTTELLYOUTHIS')
print "Logged into mail"
for item in root[0]:
	if item.tag == 'item':
		for mail_id in arr:
			msg = MIMEMultipart()
			BODY = '<a href="'+item[4].text+'">'+item[1].text+'</a>';
			SUBJECT = item[0].text
			msg['From'] = 'iwillmailyou4ever@gmail.com'
			msg['To'] = mail_id
			msg['Subject'] = 'I WILL FUCK YOU 4EVER : '+SUBJECT
			BODY = BODY.encode('ascii','ignore')
			msg.attach(MIMEText(BODY,'html'))
			mailserver.sendmail('iwillmailyou4ever@gmail.com',mail_id,msg.as_string())
			print "Mail sent to",mail_id
print "All mails sent"
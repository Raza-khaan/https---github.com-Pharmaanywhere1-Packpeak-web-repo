from datetime import datetime
from datetime import timedelta
import urllib.request

urls=["http://packpeak.com.au/pickupReport/",
"http://packpeak.com.au/checkinReport/",
"http://packpeak.com.au/nearmissReport/",
"http://packpeak.com.au/retuenReport/",
"http://packpeak.com.au/auditReport/",
"http://packpeak.com.au/noteforpatientReport/"
]
def call(url):
 urllib.request.urlopen(url)
def get_month_diff(current,nom):
    m1= current
    m2=m1 - timedelta(days=nom*25)
    m3=current
    m4=m2.replace(day=1)
    m5=m3.replace(day=1)-timedelta(days=1)
    list=str(m4).split(" ")[0].split("-")
    list.reverse()
    startDate="-".join(list)
    list1=str(m5).split(" ")[0].split("-")
    list1.reverse()
    endDate="-".join(list1)
    for i in range (0,5):
       call(urls[i]+""+startDate+"/"+endDate)

def solve():
    month=str(datetime.today()).split("-")[1]
    if month in ["01","04","07","10"] :get_month_diff(datetime.today(),3)
    if month in ["01","07"]:get_month_diff(datetime.today(),6)
    get_month_diff(datetime.today(),1)
solve()

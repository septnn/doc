#coding=utf-8
import http.client,urllib.request,urllib.parse,urllib.error,ssl
from time import ctime  
import threading 
import json 
import csv 
import copy

requrl = "http://application-tos-api/meituan/api/order/test"

point = {
         }

# pixel set 
submitCart = [ ]

postJson= {
      }
#定义需要进行发送的数据
# params = urllib.parse.urlencode(postJson);    152,247,152

 
#定义一些文件头
headers = {"Content-Type":"application/json",
           "Connection":"Keep-Alive",
           "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MjA5LCJ0aW1lIjoxNTQ4MTM2NzY2NDk3fQ.d6_qQhTCAgXUhBtVi16u3bcpsrtNd4Ro3fLUygMT97M"
		   }

# make point set 
def make_point_set(num):
       for i in range(num):
          x = i % 1000
          y = i // 1000
          point1 = copy.copy(point)
          point1["x"] = x
          point1["y"] = y 
          # print (point)
          submitCart.append(point1)



#创建请求函数
def Clean(i):
     print("thread num:%d" % i);
     #接口的url
     # requrl =""
     params = json.dumps({"i":i})
     #连接服务器	   
     conn = http.client.HTTPConnection("127.0.0.1",80)
     # print (params)
     #发送请求	   
     conn.request(method="POST",url=requrl,body=params,headers=headers)
     # 获取请求响应	   
     response=conn.getresponse()
     #打印请求状态
     print("thread num:%d start:%s" % (i,ctime()))   
     # print(response.status)
     res = response.read()
     # print (res)
     print("thread num:%d end:%s" % (i,ctime()))   


#创建数组存放线程    
threads=[] 
#创建100个线程
for i in range(16):
     #针对函数创建线程  
     t=threading.Thread(target=Clean,args=(i,))
     #把创建的线程加入线程组	 
     threads.append(t)  
 
print('start:', ctime())


if __name__ == '__main__':
     # make_point_set(500)
     # print (submitCart)
     #启动线程  
     for i in threads:  
          i.start()  
     #keep thread  
     for i in threads:  
          i.join()
     print('end:', ctime());
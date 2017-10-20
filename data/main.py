import pandas as pd
from pandas import Series,DataFrame

# numpy, matplotlib, seaborn
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
sns.set_style('whitegrid')

# machine learning
from sklearn.linear_model import LogisticRegression
from sklearn.svm import SVC, LinearSVC
from sklearn.ensemble import RandomForestClassifier
from sklearn.neighbors import KNeighborsClassifier
from sklearn.naive_bayes import GaussianNB
import json, types,string


class Icode:
    def __init__(self):
        self.rawdata = {}
        self.cleandata = {}
        self.classifiers={}
        self.answers=[]

    def process(self):
        self.readData()
        self.getStatics()
        self.transFeatures()
        self.makeTrain()
        self.trainClassifiers()

    def readData(self):
        f = open("./RawData/data.txt", "r", encoding='utf-8')
        for line in f:
            line = line.strip().split()
            if line[0] not in self.rawdata:
                self.rawdata[line[0]] = []
            self.rawdata[line[0]].append(line[1:])
        f.close()

        f = open("./RawData/answer.txt", "r", encoding='utf-8')
        for line in f:
            line = line.strip()
            self.answers.append(line)
        f.close()

    def getStatics(self):
        for id in self.rawdata:
            data = self.rawdata[id]
            pdata = {}
            pdata['features'] = []
            pdata['labels'] = []
            models = {}
            n_models = 0
            n_params = 0
            opinions = set()
            for line in data:
                if len(line) > n_models + 1:
                    n_models = len(line) - 1
                features = line[0:-1]
                labels = line[-1].strip(',').split(',')
                pdata['features'].append(features)
                pdata['labels'].append(labels)
                for model in features:
                    model = model.split(':')
                    models[model[0]] = len(model[1].strip(',').split(','))
                for label in labels:
                    opinions.add(label)
            for model in models:
                if models[model] > n_params:
                    n_params = models[model]
            statics = {}
            statics['n_models'] = n_models
            statics['n_params'] = n_params
            statics['models'] = models
            statics['opinions'] = opinions
            statics['n_types']=len(models)
            maps={}
            cnt=0
            for _ in models:
                maps[_]=cnt
                cnt+=1
            statics['maps']=maps
            pdata['statics'] = statics
            self.cleandata[id] = pdata

    def oneHot(self,n,idx):
        result=[0 for i in range(n)]
        if idx==-1:return result
        result[idx]=1
        return result

    def helper(self,features,statics):
        result=[]
        cnt=0
        for model in features:
            cnt+=1
            model=model.split(':')
            type=model[0]
            params=model[1].strip(',').split(',')
            params=[int(i) for i in params]
            type=self.oneHot(statics['n_types'],statics['maps'][type])
            l=len(params)
            while l<statics['n_params']:
                params.append(0)
                l+=1
            result.extend(type)
            result.extend(params)
        while cnt<statics['n_models']:
            cnt+=1
            result.extend(self.oneHot(statics['n_types'],-1))
            result.extend(self.oneHot(statics['n_params'],-1))
        return result



    def transFeatures(self):
        for id in self.cleandata:
            for index in range(len(self.cleandata[id]['features'])):
                self.cleandata[id]['features'][index]=self.helper(self.cleandata[id]['features'][index],self.cleandata[id]['statics'])


    def makeTrain(self):
        for id in self.cleandata:
            before=self.cleandata[id]
            after = {}
            for opinion in before['statics']['opinions']:
                data={}
                data['features']=[]
                data['labels']=[]
                for index in range(len(before['features'])):
                    data['features'].append(before['features'][index])
                    if opinion in before['labels'][index]:
                        data['labels'].append(1)
                    else:
                        data['labels'].append(0)
                after[opinion]=data
            self.cleandata[id]['data']=after
            del self.cleandata[id]['features']
            del self.cleandata[id]['labels']

    def trainClassifiers(self):
        for id in self.cleandata:
            self.classifiers[id]={}
            for opinion in self.cleandata[id]['data']:
                random_forest = RandomForestClassifier(n_estimators=100)
                random_forest.fit(self.cleandata[id]['data'][opinion]['features'],self.cleandata[id]['data'][opinion]['labels'])
                self.classifiers[id][opinion]=random_forest




icode = Icode()
icode.process()

for id in ['1','2','3']:
    icode.cleandata[id]['forweb']={}

icode.cleandata['1']['forweb']['count']={
    'A':2,
    'B':1,
    'C':2,
    'D':1,
    'E':1,
    'F':1
}
icode.cleandata['2']['forweb']['count']={
    'A':2,
    'B':4,
    'C':1
}
icode.cleandata['3']['forweb']['count']={
    'A':1,
    'B':1,
    'C':2,
    'D':2
}
for id in ['1','2','3']:
    icode.cleandata[id]['forweb']['models']=icode.cleandata[id]['statics']['models']

# print((json.dumps(icode.cleandata['1']['forweb'])).encode('utf8'))

import socket


HOST = '0.0.0.0'
PORT = 3434

s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)  #创建一个socket
s.bind((HOST,PORT)) #绑定socket
s.listen(10) #开始监听



while True:
    conn,addr = s.accept() #接受客户端连接请求
    data=conn.recv(1024).decode('utf8')
    print(data)
    try:
        data=data.strip().split()

        type=data[0]

        if type=='0':
            id = data[1]
            conn.send((json.dumps(icode.cleandata[id]['forweb'])).encode('utf8'))
        else:
            id=data[1]
            data=data[2:]
            features=[]
            features.append(icode.helper(data,icode.cleandata[id]['statics']))
            ans=""
            for opinion in icode.classifiers[id]:
                if icode.classifiers[id][opinion].predict(features)==1:
                    ans+=" "+opinion
            conn.send(ans.encode('utf8'))  # 向客户端返回数据
    except:

        conn.send("Illegal data".encode('utf8'))

conn.close()
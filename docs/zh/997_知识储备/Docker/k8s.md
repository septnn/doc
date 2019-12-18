# k8s

## 安装

### win10

- 安装docker
- 下载[kubectl.exe](https://storage.googleapis.com/kubernetes-release/release/v1.17.0/bin/windows/amd64/kubectl.exe)
- 加入path

- 安装minikube
- 下载[minikube](https://github-production-release-asset-2e65be.s3.amazonaws.com/56353740/2bec8300-1c16-11ea-9c33-8b6bdd75052d?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAIWNJYAX4CSVEH53A%2F20191217%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20191217T055042Z&X-Amz-Expires=300&X-Amz-Signature=4f1813f5db43b30700d6a572e64d2df2da385a33db8c8e4ab2695bd8fbbf464a&X-Amz-SignedHeaders=host&actor_id=3860032&response-content-disposition=attachment%3B%20filename%3Dminikube-windows-amd64.exe&response-content-type=application%2Foctet-stream)
- 加入path

- 启动minikube
- 替换docker hub 为国内源`--registry-mirror=https://registry.docker-cn.com`
  - docker-machine ssh
  - sudo vi /var/lib/boot2docker/profile
  - 在`--label provider=virtualbox`下添加`--registry-mirror=https://registry.docker-cn.com`
  - docker-machine restart
- 修改dns
  - docker-machine ssh
  - sudo vi /etc/resolv.conf
  - 添加 `nameserver 114.114.114.114` `nameserver 8.8.8.8`
- 下载kube相关镜像 win10

```sh
docker pull mirrorgooglecontainers/kube-apiserver-amd64:v1.17.0-alpha.2
docker pull mirrorgooglecontainers/kube-controller-manager-amd64:v1.17.0-alpha.2
docker pull mirrorgooglecontainers/kube-scheduler-amd64:v1.17.0-alpha.2
docker pull mirrorgooglecontainers/kube-proxy-amd64:v1.17.0-alpha.2
docker pull mirrorgooglecontainers/pause-amd64:3.1
docker pull mirrorgooglecontainers/etcd-amd64:3.4.2-0
docker pull coredns/coredns:1.6.6

docker tag mirrorgooglecontainers/kube-apiserver-amd64:v1.17.0-alpha.2 k8s.gcr.io/kube-apiserver:v1.17.0
docker tag mirrorgooglecontainers/kube-controller-manager-amd64:v1.17.0-alpha.2 k8s.gcr.io/kube-controller-manager:v1.17.0
docker tag mirrorgooglecontainers/kube-scheduler-amd64:v1.17.0-alpha.2 k8s.gcr.io/kube-scheduler:v1.17.0
docker tag mirrorgooglecontainers/kube-proxy-amd64:v1.17.0-alpha.2 k8s.gcr.io/kube-proxy:v1.17.0
docker tag mirrorgooglecontainers/pause-amd64:3.1 k8s.gcr.io/pause:3.1
docker tag mirrorgooglecontainers/etcd-amd64:3.4.2-0 k8s.gcr.io/etcd:3.4.3-0
docker tag coredns/coredns:1.6.6 k8s.gcr.io/coredns:1.6.5
```
- 启动minikube `minukube start --vm-version=virtualbox`
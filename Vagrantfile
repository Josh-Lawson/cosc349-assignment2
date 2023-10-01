
Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/focal64"
  config.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
  config.vm.provision "shell", inline: <<-SHELL
    apt-get update
    apt-get install -y python3-pip awscli gnupg software-properties-common
    wget -O- https://apt.releases.hashicorp.com/gpg | gpg --dearmor >/usr/share/keyrings/hashicorp-archive-keyring.gpg
    gpg --no-default-keyring --keyring /usr/share/keyrings/hashicorp-archive-keyring.gpg --fingerprint
    echo "deb [signed-by=/usr/share/keyrings/hashicorp-archive-keyring.gpg] https://apt.releases.hashicorp.com $(lsb_release -cs) main" >/etc/apt/sources.list.d/hashicorp.list
    apt-get update
    apt-get install terraform
    sudo apt install mysql-client-core-8.0
    runuser -l vagrant -c 'mkdir ~/.aws'
    export LC_ALL="en_US.UTF-8"
    pip3 install boto3
    pip3 install --upgrade awscli
  SHELL
end

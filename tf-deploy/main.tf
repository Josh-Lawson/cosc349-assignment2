provider "aws" {
  region = "us-east-1"
}

variable "private_key_path" {
  description = "Path to the SSH private key"
  type        = string
}

resource "aws_security_group" "allow_ssh" {
  name        = "allow_ssh"
  description = "Allow inbound SSH traffic"

  ingress {
    description = "SSH from anywhere"
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_instance" "user_interface" {
  ami           = "ami-010e83f579f15bba0"
  instance_type = "t2.micro"
  key_name      = "cosc349-2023"


  vpc_security_group_ids = [aws_security_group.allow_ssh.id]

  user_data = <<-EOF
              #!/bin/bash
              sudo apt update
              sudo apt install -y apache2 php libapache2-mod-php php-mysql awscli
              sudo a2dissite 000-default
              cp /opt/rms-app/user-website.conf /etc/apache2/sites-available/
              sudo a2ensite user-website
              sudo service apache2 restart
              EOF

  tags = {
    Name = "UserInterface"
  }

  provisioner "file" {
    source      = "user-website.conf"
    destination = "/opt/rms-app/user-website.conf"

    connection {
      type        = "ssh"
      user        = "ec2-user"
      private_key = file(var.private_key_path)
      host        = self.public_ip
    }
}
}

resource "aws_instance" "admin_interface" {
  ami           = "ami-010e83f579f15bba0"
  instance_type = "t2.micro"
  key_name      = "cosc349-2023"


  vpc_security_group_ids = [aws_security_group.allow_ssh.id]

  user_data = <<-EOF
              #!/bin/bash
              sudo apt update
              sudo apt install -y apache2 php libapache2-mod-php php-mysql awscli
              sudo a2dissite 000-default
              cp /opt/rms-app/user-website.conf /etc/apache2/sites-available/
              sudo a2ensite admin-website
              sudo systemctl reload apache2
              EOF

  tags = {
    Name = "AdminInterface"
  }

  provisioner "file" {
    source      = "admin-website.conf"
    destination = "/opt/rms-app/admin-website.conf"

    connection {
      type        = "ssh"
      user        = "ec2-user"
      private_key = file(var.private_key_path)
      host        = self.public_ip
    }
}

}

output "user_interface" {
  value = aws_instance.admin_interface.public_ip
}

output "admin_interface" {
  value = aws_instance.user_interface.public_ip
}


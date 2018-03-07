#!/usr/bin/python3

import re
import os
import glob

class App:

    MODE_APACHE = 0
    MODE_NGINX = 1

    NGINX_VHOSTS = "/etc/nginx/sites-enabled/"
    NGINX_VHOST_PATTERN = "*"
    NGINX_SITENAME_PATTERN = r"(\s+(?:www\.)?[a-z]+\.[a-z]{1,3})"
    NGINX_DIRECTIVE_NAME = "server_name"

    @classmethod
    def run(cls, mode=MODE_NGINX):
        c = cls()

    def __init__(self, mode):
        self.mode = mode
        self.domains = []

    def search_domains(self):
        self.mode = self.search_apache() if self.mode == self.MODE_APACHE else self.search_nginx()

    def search_apache(self):
        pass

    def search_nginx(self):
        cre = re.compile(self.NGINX_SITENAME_PATTERN)
        for file in glob.glob(os.path.abspath(self.NGINX_VHOSTS) + self.NGINX_VHOST_PATTERN):
            for _, line in enumerate(open(os.path.abspath(file))):
                if self.NGINX_DIRECTIVE_NAME not in line:
                    continue
                for matches in cre.finditer(line):
                    self.domains.append("".join(matches.split()))





if __name__ == "__main__":
    App.run()

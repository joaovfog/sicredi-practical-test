import requests
from bs4 import BeautifulSoup

# URL da aplicacao Laravel
base_url = "http://127.0.0.1:8000"

# inicia uma sessao para gerenciar cookies automaticamente
session = requests.Session()

# pega a página de login primeiro para capturar o token
login_page_url = f"{base_url}/login"
login_page = session.get(login_page_url)

# parse da pagina html para capturar o token
soup = BeautifulSoup(login_page.content, 'html.parser')
csrf_token = soup.find('input', {'name': '_token'})['value']

# dados de login
login_data = {
    'email': 'joao@gmail.com',
    'password': '12345678',
    '_token': csrf_token
}

# faz a requisicao post para o endpoint login
login_url = f"{base_url}/login"
response = session.post(login_url, data=login_data)

# verifica se o login teve sucesso
if response.ok and 'users' in response.url:
    print("Login realizado com sucesso!")
    
    # caputra os cookies da sessao e os utiliza em outras requisicoes
    print("Cookies de sessão:", session.cookies.get_dict())
    
    users_url = f"{base_url}/users"
    users_page = session.get(users_url)
    
    if users_page.ok:
        print("Página de usuários acessada com sucesso!")
        print(users_page.text)
else:
    print("Falha ao realizar login:", response.status_code)
    print(response.text)

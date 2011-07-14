def authenticate
  require 'net/http'
  url = URI.parse('https://pi.pardot.com/api/login/version/3')
  # build the params string
  post_args1 = { 'username' => params[:username], 'user_key' => params[:user_key] , 'password' => params[:password]  }
  # send the request
  resp, data = Net::HTTP.post_form(url, post_args1)
end

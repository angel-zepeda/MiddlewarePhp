<?php
  
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <script crossorigin
      src="https://unpkg.com/react@16/umd/react.development.js"></script>
    <script crossorigin
      src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script crossorigin
      src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.js"></script>
      <link rel="stylesheet" href="./styles.css" />
  </head>
  <body>
    <div id="root"></div>

    <script type="text/babel">  

    class App extends React.Component {

      state = {
        input: '',
        initialState: '',
        response: [],
        // urlRoot: 'https://dialogflow.googleapis.com/v2',
        // token: 'ya29.c.El9eB93y5hhJXMu8EqRvVmo-pAAyaKGznXrVqwit_-xCtpanHObsnqRz70khYTa403Le4EVoArtEEM1HSP8P_tFzji-5YZ-v7RSgTvap2OSqrfy6JBhEc_LpKwd5VCYxlA',
        msg: [],
        show: false,
      }
     
      toggleChat = () => this.setState({ show: !this.state.show });
      handleOnChange = e => this.setState({ [e.target.name]: e.target.value });

      handleOnSubmit = (e) => {
        const { input } = this.state;
        e.preventDefault();
        this.replaceWhiteSpace(input);        
      }

      replaceWhiteSpace = (string) => {
        const query = string.replace(/\s/g, "+");
        this.getResponse(query);
      }

      getResponse = async (query) => {
        // const URL = `${this.state.urlRoot}/projects/${this.state.projectId}/agent/sessions/${this.state.sessionId}:detectIntent`;
        let headers = { headers: { 'Authorization': 'Bearer ' + this.state.token, 'Content-Type': 'application/json' } }
        let reponseReq = await axios.get(`http://localhost/MiddlewarePhp/tokenAccess.php?query=${query}`);
        console.log(reponseReq);
        this.setState({ response: reponseReq.data })
        this.setState({ 
            msg: [ ...this.state.msg, {user: this.state.input, bot: this.state.response} ], 
            input: this.state.initialState
        })
        let chat = document.querySelector('#chatBox');
        if (chat) chat.scrollTop = chat.scrollHeight;  
      }

      render() {
        
        if (this.state.show) {          
          return (
            <div> 
                <div className="chat-client">
                  <div className="chat-header"> 
                    <p> CHAT BOT</p>
                  </div>
                    <div className="chat-box" id="chatBox">
                    {this.state.msg.map(msg => (
                        <div key={msg.user}>
                            <p className="user-chat">{msg.user}</p>       
                            <p className="bot-chat">{msg.bot}</p>
                        </div>              
                        ))}
                    </div>
                <form onSubmit={this.handleOnSubmit}>
                    <input 
                    type="text"
                    onChange={this.handleOnChange}
                    value={this.state.input}
                    name="input"
                    className="input-chat"
                    // autoFocus={true} 
                    placeholder="Escribe un mensaje..."
                    />
                    <input 
                    type="submit"
                    value="Enviar"
                    className="btn-send"
                    />
                </form>
                </div>
                <input 
                    type="button"
                    value="X"
                    className="btn-close-chat"
                    onClick={this.toggleChat}
                    />
            </div>
        )
        }else {
          return (
            <input 
              type="button"
              value="Abrir Chat"
              onClick={this.toggleChat}
              className="btn-open-chat"
            />
          )
          
        }
       
      }
    }     
     ReactDOM.render(<App />,document.getElementById("root") );
    </script>
  </body>
</html>
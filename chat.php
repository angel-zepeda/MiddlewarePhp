<?php?>
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
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
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
            session: Math.random()
        }

        componentDidMount() {
            sessionStorage.setItem('session', this.state.session);
        }

        toggleChat = () => this.setState({ show: !this.state.show });
        handleOnChange = e => this.setState({ [e.target.name]: e.target.value });

        handleOnSubmit = (e) => {
            const { input } = this.state;
            e.preventDefault();
            this.replaceWhiteSpace(input);
            this.setState({
            input: this.state.initialState
            })
            
        }

      replaceWhiteSpace = string => {
        const query = string.replace(/\s/g, "+");
        
        this.getResponse(query, string);
      }

      getResponse = async (query, string) => {
        // const URL = `${this.state.urlRoot}/projects/${this.state.projectId}/agent/sessions/${this.state.sessionId}:detectIntent`;
        let headers = { headers: { 'Authorization': 'Bearer ' + this.state.token, 'Content-Type': 'application/json' } }
        let reponseReq = await axios.get(`http://localhost/MiddlewarePhp/tokenAccess.php?query=${query}&session=${sessionStorage.getItem('session')}`);
        console.log(reponseReq);
        this.setState({ response: reponseReq.data })
        this.setState({ 
            msg: [ ...this.state.msg, {user: string, bot: this.state.response} ]
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
                    <p>Chat</p>
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
                    <button
                      type="submit"
                      className="btn-send"                      
                    >
                    <i class="material-icons">send</i>
                    </button>
                </form>
                </div>
                <input 
                    type="button"
                    value="&times;"
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
     ReactDOM.render(<App />,document.getElementById("root"));
    </script>
  </body>
</html>

<!-- <iframe
    allow="microphone;"
    width="350"
    height="430"
    src="https://console.dialogflow.com/api-client/demo/embedded/877e37a3-420b-475e-9727-93c774438af4">
</iframe> -->
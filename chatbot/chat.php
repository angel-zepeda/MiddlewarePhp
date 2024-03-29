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
            welcome: '',
            msg: [],
            show: false,
            session: Math.random()
        }

        getInitialMessage = async () => {
          console.log("HOla")
          let start_message = "Hola";
          let headers = { headers: { 'Authorization': 'Bearer ' + this.state.token, 'Content-Type': 'application/json' } }
          let reponseReq = await axios.get(`https://aspacei.net/MiddlewarePhp/tokenAccess.php?query=${start_message}&session=${sessionStorage.getItem('session')}`);
          this.setState({ msg: [ ...this.state.msg, {user: '', bot: reponseReq.data }] });
        }

        componentDidMount() { 
          sessionStorage.setItem('session', this.state.session); 
          this.getInitialMessage();
        }

        toggleChat = () => this.setState({ show: !this.state.show });
        handleOnChange = e => this.setState({ [e.target.name]: e.target.value });

        handleOnSubmit = (e) => {
            const { input } = this.state;
            e.preventDefault();
            this.replaceWhiteSpace(input);
            this.setState({ input: this.state.initialState });
        }

      replaceWhiteSpace = string => {
        const query = string.replace(/\s/g, "+");
        this.getResponse(query, string);
      }

      getResponse = async (query, string) => {
        let headers = { headers: { 'Authorization': 'Bearer ' + this.state.token, 'Content-Type': 'application/json' } }
        let reponseReq = await axios.get(`https://aspacei.net/MiddlewarePhp/tokenAccess.php?query=${query}&session=${sessionStorage.getItem('session')}`);
        this.setState({ msg: [ ...this.state.msg, {user: string, bot: reponseReq.data}] });
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
                    {
                      this.state.msg.map(msg => (
                        <div key={msg.user}>
                          { msg.user === '' ? null : <p className="user-chat">{msg.user}</p> }
                          <p className="bot-chat">{msg.bot}</p>
                        </div>
                      ))
                    }
                    </div>
                    <div className="chat-form">
                      <form onSubmit={this.handleOnSubmit}>
                          <input
                          type="text"
                          onChange={this.handleOnChange}
                          value={this.state.input}
                          name="input"
                          className="input-chat"
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
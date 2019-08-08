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
  </head>
  <body>
    <div id="root"></div>

    <script type="text/babel">  

    class App extends React.Component {

      state = {
        input: '',
        initialState: '',
        response: [],
        urlRoot: 'https://dialogflow.googleapis.com/v2',
        token: 'ya29.c.El9eB93y5hhJXMu8EqRvVmo-pAAyaKGznXrVqwit_-xCtpanHObsnqRz70khYTa403Le4EVoArtEEM1HSP8P_tFzji-5YZ-v7RSgTvap2OSqrfy6JBhEc_LpKwd5VCYxlA',
        msg: [],
        show: false,
        projectId: 'chatbotbasico',
        sessionId: '1542184231687'
      }
     
      toggleChat = () => {
        this.setState({ show: !this.state.show })
      }

      handleOnChange = e => {
        this.setState({ [e.target.name]: e.target.value })       
      }

      handleOnSubmit = (e) => {
        const { input } = this.state;
        e.preventDefault();
        this.replaceWhiteSpace(input);        
      }

      replaceWhiteSpace = (string) => {
        const query = string.replace(/\s/g, "+");
        var body = { "queryInput": { "text": { "text": query, "languageCode": "es" } }, };
        this.getResponse(body);
      }

      getResponse = async (body) => {
        const URL = `${this.state.urlRoot}/projects/${this.state.projectId}/agent/sessions/${this.state.sessionId}:detectIntent`;
        let headers = { headers: { 'Authorization': 'Bearer ' + this.state.token, 'Content-Type': 'application/json' } }
        let reponseReq = await axios.post(URL, body, headers);
        this.setState({ response: reponseReq.data.queryResult.fulfillmentMessages })
        this.setState({ 
            msg: [ ...this.state.msg, {user: this.state.input, bot: this.state.response[0].text.text} ], input: this.state.initialState
        })
        let chat = document.querySelector('#chatBox');
        if (chat) chat.scrollTop = chat.scrollHeight;  
      }

      render() {
        const CHAT_CLIENT = {
          width: '40%',
          height: '350px',
          border: '1px solid gray',
          borderRadius: '2%',
          padding: '10px',
          boxShadow: '1px 1px 3px #888888',
          backgroundColor: '#E0FFFF'
        }
        const CHAT_BOX = {
          width: '95%',
          height: '80%',
          border: '1px solid gray',
          backgroundColor: '#fff',
          padding: '10px',
          overflowY: 'scroll',
          overflowX: 'hidden',
          fontSize: '0.9em'
        }
        const INPUT_CHAT = {
          width: '76%',
          borderRadius: '1%',
          fontSize: '1em',
          border: '1px solid skyblue',
          paddingTop: '10px',
          margin: '10px',
          fontSize: '0.9em',
        }

        const SEND_BTN = {
          backgroundColor: 'blue',
          color: 'white',
          fontSize: '1.3em',
          border: 'none',
          padding: '10px',
          borderRadius: '4px'
        }

        const USER_CHAT = {
          width: '80%',
          background: 'Green',
          padding: '10px',
          float: 'right',
          textAlign: 'right',
          margin: '0',
          color: '#fff',
          borderRadius: '50px',
          marginBottom: '10px'
          
        }
        const BOT_CHAT = {
          width: '80%',
          padding: '10px',
          float: 'left',
          margin: '0',
          background: 'SteelBlue',
          borderRadius: '50px',
          color: '#fff',
          marginBottom: '10px'

        }
        
        if (this.state.show) {          
          return (
            <div> 
                <div style={CHAT_CLIENT}>
                    <div style={CHAT_BOX} id="chatBox">
                    {this.state.msg.map(msg => (
                        <div key={msg.user}>
                            <p style={USER_CHAT}>{msg.user}</p>       
                            <p style={BOT_CHAT}>{msg.bot}</p>
                        </div>              
                        ))}
                    </div>
                <form onSubmit={this.handleOnSubmit}>
                    <input 
                    type="text"
                    onChange={this.handleOnChange}
                    value={this.state.input}
                    name="input"
                    style={INPUT_CHAT}
                    autoFocus={true} 
                    />
                    <input 
                    type="submit"
                    value="Send"
                    style={SEND_BTN}              
                    />
                </form>
                </div>
                <input 
                    type="button"
                    value="Cerrar CHat"
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
            />
          )
          
        }
       
      }
    }     
     ReactDOM.render(<App />,document.getElementById("root") );
    </script>
  </body>
</html>
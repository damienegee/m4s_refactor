import styled from "@emotion/styled";
import Button from "@mui/material/Button";
const ButtonT = styled(Button)(
    {
        marginTop:20,
        padding:10,
        marginLeft:25,
        borderRadius: 35,
        border:'1px solid black',
        backgroundColor: "white",
        color:'black',
        variant: "outlined"  
    }
  );
  interface Butn {
    text:string,
    size:number,
    Icon : React.ReactNode
}

  const Btn = ({text,size,Icon}:Butn) => {
    return (
        <ButtonT className="btn" style={{fontSize:size}} endIcon={Icon} >{text}</ButtonT>
    )
    
  }

  export default Btn;
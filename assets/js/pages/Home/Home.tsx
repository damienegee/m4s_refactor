import Header from "../../components/Header/Header";
import Sidebar from "../../components/Sidebar/Sidebar";
import "./Home.css"
const Home = () => {
  return (
    <div className="container">
        <Sidebar/>
        <div className="homeContainer">
            <Header title="My Dashboard"/>
            Content
        </div>
    </div>
  )
}

export default Home

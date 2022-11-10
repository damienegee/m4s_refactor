import Header from "../../components/Header/Header";
import Sidebar from "../../components/Sidebar/Sidebar";
import styles from  "../page.module.css"
const Home = () => {
  return (
    <div className={styles.container}>
        <Sidebar/>
        <div className={styles.pageContainer}>
            <Header title="My Dashboard"/>
            Content
        </div>
    </div>
  )
}

export default Home

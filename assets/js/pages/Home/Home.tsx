import { useState, useEffect, MouseEventHandler } from "react";
import Layout from "../../components/Layout/Layout";
import AddWidget from "../../components/Widgets/AddWidget/AddWidget";
import Counter from "../../components/Widgets/Counter/Counter";
import DoughnutChart from "../../components/Widgets/DoughnutChart/DoughnutChart";
import List from "../../components/Widgets/List/List";
import styles from "./Home.module.css"

const Home = () => {
  const [editState, setEditState] = useState<boolean>(false);
  const [widgets, setWidgets] = useState<string[]>(localStorage.getItem("widgets")?.split(",") ??
    [
      "doughnut-chart",
      "devices-counter",
      "incidents-counter",
      "users-counter",
      "images-list",
      "mails-list",
      "incidents-list"
    ]);

  useEffect(() => {
    localStorage.setItem("widgets", widgets.join(","));
  }, [widgets]);
  const handleRemove: MouseEventHandler<HTMLSpanElement> = (event) => {
    console.log(event.currentTarget.dataset.name);
    setWidgets(widgets.filter(widget => widget != event.currentTarget.dataset.name));
  }

  return (
    <Layout title="My Dashboard" editState={editState} setEditState={setEditState}>
      <div className={styles.widgetsContainer}>
        {widgets.map(widget => {
          if (widget == "devices-counter") return <Counter key={widget} type="devices" editState={editState} name={widget} handleRemove={handleRemove} />
          else if (widget == "incidents-counter") return <Counter key={widget} type="incidents" editState={editState} name={widget} handleRemove={handleRemove} />
          else if (widget == "users-counter") return <Counter key={widget} type="users" editState={editState} name={widget} handleRemove={handleRemove} />
          else if (widget == "images-list") return <List key={widget} type="images" editState={editState} name={widget} handleRemove={handleRemove} />
          else if (widget == "mails-list") return <List key={widget} type="mails" editState={editState} name={widget} handleRemove={handleRemove} />
          else if (widget == "incidents-list") return <List key={widget} type="incidents" editState={editState} name={widget} handleRemove={handleRemove} />
          else if (widget == "doughnut-chart") return <DoughnutChart editState={editState} name={widget} handleRemove={handleRemove} />
        })}
        {editState && <AddWidget />}
      </div>
    </Layout>
  )
}

export default Home

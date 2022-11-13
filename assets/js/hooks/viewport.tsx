import {createContext,useState,useEffect,useContext} from "react";
type ViewportType = {
    width:number
}
const viewportContext = createContext<ViewportType>({width:0});

export const ViewportProvider = ({children} : any) => {
  const [width, setWidth] = useState(window.innerWidth);

  const handleResize = () => {
    setWidth(window.innerWidth);
  }

useEffect(() => {
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);
  return (
    <viewportContext.Provider value={{ width}}>
      {children}
    </viewportContext.Provider>
  );
};

export const useViewport = () => {
  const {width} = useContext(viewportContext);
  return {width};
}
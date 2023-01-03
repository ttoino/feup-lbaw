import { renderSingleton } from "../../render";
import { Project } from "../../types/project";

export const renderProject = renderSingleton<Project>(".project-info .left");

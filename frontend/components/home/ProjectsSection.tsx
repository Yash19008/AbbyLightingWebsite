import type { Project } from "@/types/project";

interface ProjectsSectionProps {
  projects?: Project[];
}

export default function ProjectsSection({ projects = [] }: ProjectsSectionProps) {
  // If no dynamic projects exist, hide the entire section (including heading)
  if (!projects || projects.length === 0) {
    return null;
  }

  // Ensure we always have exactly 6 items to maintain the CSS grid layout
  const displayProjects = [...projects];
  while (displayProjects.length < 6) {
    displayProjects.push({
      id: `empty-${displayProjects.length}`,
      isPlaceholder: true,
    } as unknown as Project);
  }

  return (
    <section className="section projects" id="projects">
      <div className="shell">
        <div className="section-head reveal" suppressHydrationWarning>
          <h2>Latest projects</h2>
          <a className="project-all-link" href="https://abbylighting.com/projects">
            <span className="lbl-d">View all projects</span>
            <span className="lbl-m">View All</span>
            <svg 
              className="project-all-arrow" 
              width="24" 
              height="24" 
              viewBox="0 0 24 24" 
              fill="none" 
              stroke="currentColor" 
              strokeWidth="1.25" 
              strokeLinecap="round" 
              strokeLinejoin="round" 
              aria-hidden="true"
            >
              <line x1="4" y1="12" x2="20" y2="12"></line>
              <polyline points="14 6 20 12 14 18"></polyline>
            </svg>
          </a>
        </div>
        <div className="project-grid">
          {displayProjects.map((project, index) => {
            if ((project as unknown as { isPlaceholder?: boolean }).isPlaceholder) {
              return (
                <a 
                  key={project.id} 
                  className="project placeholder" 
                  style={{"--i": index, pointerEvents: "none", visibility: "hidden"} as React.CSSProperties}
                  aria-hidden="true"
                >
                  <div className="project-placeholder" aria-hidden="true"><span>Project image</span></div>
                </a>
              );
            }

            return (
              <a 
                key={project.id} 
                href={`/projects/${project.slug}`} 
                className="project" 
                style={{"--i": index} as React.CSSProperties}
              >
                {project.image_url ? (
                  <img src={project.image_url} alt={project.name} />
                ) : (
                  <div className="project-placeholder" aria-hidden="true"><span>Project image</span></div>
                )}
                <figcaption>
                  <strong>{project.name}</strong>
                  <span>{project.type}{project.location && ` | ${project.location}`}</span>
                </figcaption>
              </a>
            );
          })}
        </div>
      </div>
    </section>
  );
}

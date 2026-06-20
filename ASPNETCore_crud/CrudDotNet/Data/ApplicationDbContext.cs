using Microsoft.EntityFrameworkCore;
using CrudDotNet.Models;

namespace CrudDotNet.Data
{
    public class ApplicationDbContext : DbContext
    {
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options) : base(options)
        {
        }

        // Esta propiedad representa la tabla en la base de datos
        public DbSet<Producto> Productos { get; set; }
    }
}
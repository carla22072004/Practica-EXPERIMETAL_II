using System.ComponentModel.DataAnnotations;

namespace CrudDotNet.Models
{
    public class Producto
    {
        [Key] // Le dice a .NET que este es el ID autoincremental
        public int Id { get; set; }

        [Required(ErrorMessage = "El nombre es obligatorio")]
        [StringLength(100)]
        public string Nombre { get; set; } = string.Empty;

        [Required(ErrorMessage = "El precio es obligatorio")]
        [Range(0.01, double.MaxValue, ErrorMessage = "El precio debe ser mayor a 0")]
        public decimal Precio { get; set; }
    }
}
